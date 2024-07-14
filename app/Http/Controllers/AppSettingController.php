<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Services\StorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AppSettingController extends Controller
{
    public function __construct(public StorageService $storageService)
    {
    }

    public function index()
    {
        return view('app-settings.index', [
            'title' => 'Pengaturan Website',
        ]);
    }

    public function update(Request $request)
    {
        $validatedData = $this->validate($request, [
            'web_name' => 'required',
            'web_description' => 'max:1000',
            'footer' => 'max:256',
            'logo' => 'sometimes|image|max:3072|nullable',
            'logo_icon' => 'sometimes|image|max:512|nullable',
            'login_background' => 'sometimes|image|max:1024|nullable',
        ], attributes: [
            'web_name' => 'Nama Website',
            'web_description' => 'Keterangan Website',
            'footer' => 'Footer Website',
            'logo' => 'Logo',
            'logo_icon' => 'Logo Icon',
            'login_background' => 'Login Background',
        ]);

        $appSetting = AppSetting::first();
        $appSetting->update(array_merge($validatedData, [
            'logo' => $this->storageService
                ->public()
                ->uploadOrReturnDefault(
                    'logo',
                    $appSetting->logo,
                    'app-settings'
                ),
            'logo_icon' => $this->storageService
                ->public()
                ->uploadOrReturnDefault(
                    'logo_icon',
                    $appSetting->logo_icon,
                    'app-settings'
                ),
            'login_background' => $this->storageService
                ->public()
                ->uploadOrReturnDefault(
                    'login_background',
                    $appSetting->login_background,
                    'app-settings'
                ),
        ]));

        return redirect()
            ->route('app-settings.index')
            ->with('success', 'Data pengaturan website berhasil diperbarui.');
    }

    public function appSettingImageDestroy(Request $request)
    {
        $validatedData = $this->validate($request, [
            'type' => 'required|in:login_background,logo,logo_icon',
        ], attributes: [
            'type' => 'Tipe',
        ]);

        $appSetting = AppSetting::first();

        Storage::delete($appSetting->{$validatedData['type']});

        $appSetting->update([
            $validatedData['type'] => null,
        ]);

        return redirect()->route('app-settings.index')
            ->with('success', 'Data pengaturan website berhasil diperbarui.');
    }
}
