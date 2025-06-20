<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PublishController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $business = $user->business;

        if (!$business) {
            return redirect()->route('user.business.index')
                ->with('warning', 'Lengkapi data usaha terlebih dahulu sebelum publikasi.');
        }

        return view('user.publish.index', compact('user', 'business'));
    }

    public function togglePublishStatus(Request $request)
    {
        $user = Auth::user();
        $business = $user->business;

        if (!$business) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Profil bisnis tidak ditemukan.'], 404);
            }
            return redirect()->back()->with('error', 'Profil bisnis tidak ditemukan.');
        }

        try {
            if (!$business->isReadyToPublish() && $request->publish_status) {
                $message = 'Lengkapi minimal 80% profil usaha Anda sebelum publikasi.';
                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => $message], 422);
                }
                return redirect()->back()->with('warning', $message);
            }

            if (empty($business->public_url) && $request->publish_status) {
                $business->generateBusinessUrl();
            }

            if ($request->publish_status) {
                $business->publish();
                $message = 'Website berhasil dipublikasikan!';
            } else {
                $business->unpublish();
                $message = 'Website dinonaktifkan.';
            }

            log_activity('Updated publish status', $business);

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => $message]);
            }
            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            \Log::error('Publish toggle error: ' . $e->getMessage());
            $errorMessage = 'Gagal mengubah status publikasi. Silakan coba lagi.';
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $errorMessage], 500);
            }
            return redirect()->back()->with('error', $errorMessage);
        }
    }


    public function updateUrl(Request $request)
    {
        $user = Auth::user();
        $business = $user->business;

        if (!$business) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Profil bisnis tidak ditemukan.'], 404);
            }
            return redirect()->back()->with('error', 'Profil bisnis tidak ditemukan.');
        }

        $request->validate([
            'url_slug' => 'required|string|min:3|max:100|regex:/^[a-z0-9-]+$/'
        ], [
            'url_slug.required' => 'URL tidak boleh kosong.',
            'url_slug.regex' => 'URL hanya boleh mengandung huruf kecil, angka, dan tanda hubung.',
            'url_slug.min' => 'URL minimal 3 karakter.',
            'url_slug.max' => 'URL maksimal 100 karakter.'
        ]);

        try {
            $slug = $request->url_slug;
            $exists = \App\Models\Business::where('public_url', 'like', "%/{$slug}")
                ->where('id', '!=', $business->id)
                ->exists();

            if ($exists) {
                $errorMessage = 'URL sudah digunakan. Silakan pilih URL lain.';
                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => $errorMessage, 'errors' => ['url_slug' => [$errorMessage]]], 422);
                }
                return redirect()->back()->with('error', $errorMessage);
            }

            $newUrl = url('/' . $slug);
            $business->update(['public_url' => $newUrl]);
            log_activity('Updated business URL', $business);

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'URL website berhasil diperbarui.']);
            }
            return redirect()->back()->with('success', 'URL website berhasil diperbarui.');
        } catch (\Exception $e) {
            \Log::error('URL update error: ' . $e->getMessage());
            $errorMessage = 'Gagal memperbarui URL. Silakan coba lagi.';
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $errorMessage], 500);
            }
            return redirect()->back()->with('error', $errorMessage);
        }
    }

    public function displayQrCode()
    {
        $business = Auth::user()->business;
        if (!$business || !$business->public_url) {
            return response('URL not set', 404);
        }
        $qrCodeImage = QrCode::format('png')->size(300)->margin(1)->generate($business->public_url);

        return response($qrCodeImage)->header('Content-Type', 'image/png');
    }

    public function downloadQrCode()
    {
        $business = Auth::user()->business;

        if (!$business || !$business->public_url) {
            return redirect()->back()->with('error', 'URL website belum diatur untuk membuat QR Code.');
        }

        try {
            $qrCodeImage = QrCode::format('png')->size(500)->margin(1)->generate($business->public_url);
            
            $filename = "qr-code-" . Str::slug($business->business_name) . ".png";

            return response($qrCodeImage)
                    ->header('Content-Type', 'image/png')
                    ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');

        } catch (\Exception $e) {
            \Log::error('On-the-fly QR Code download error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal membuat QR Code untuk diunduh. Silakan coba lagi.');
        }
    }

}