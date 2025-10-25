@php
    // Photo
    $file = $form->files ?? null;
    $photoUrl = $file && $file->id_picture
        ? \Illuminate\Support\Facades\Storage::url($file->id_picture)
        : asset('images/default-photo.png');

    // Logos (public/images)
    $flag = asset('images/philippine-flag-small.png');
    $greenLogo = asset('images/calapan_flag.png');
    $blueLogo = asset('images/pdao_logo.png');

    // Mayor info (temporary placeholders; can be dynamic)
    $mayorName = $mayorName ?? 'ATTY. DOY C. LEACHON';
    $mayorSignature = isset($mayorSignature) ? asset('storage/'.$mayorSignature) : null;

    // Scaling factor for preview vs print
    $scale = isset($preview) && $preview ? 1.35 : 1;
@endphp

<div
    style="
        width:calc({{ 85.6 * $scale }}mm);
        height:calc({{ 54 * $scale }}mm);
        background:#fff;
        border-radius:6px;
        box-shadow:0 2px 6px rgba(0,0,0,0.15);
        padding:{{ 4 * $scale }}mm;
        box-sizing:border-box;
        font-family: Arial, Helvetica, sans-serif;
        display:flex;
        flex-direction:column;
        justify-content:space-between;
    "
>
    @if($side === 'front')
        {{-- FRONT SIDE --}}
        <div style="display:flex; justify-content:space-between; height:100%;">
            {{-- LEFT INFO --}}
            <div style="flex:1; display:flex; flex-direction:column; justify-content:space-between;">
                {{-- HEADER --}}
                <div>
                    <div style="display:flex; align-items:center; gap:{{ 2.5 * $scale }}mm; margin-bottom:{{ 2 * $scale }}mm;">
                        <img src="{{ $flag }}" alt="flag" style="height:{{ 8 * $scale }}mm;">
                        <div style="display:flex; gap:{{ 1 * $scale }}mm;">
                            <img src="{{ $greenLogo }}" alt="green" style="height:{{ 8 * $scale }}mm;">
                            <img src="{{ $blueLogo }}" alt="blue" style="height:{{ 8 * $scale }}mm;">
                        </div>
                    </div>

                    <div style="text-align:center; line-height:1;">
                        <div style="font-weight:700; font-size:{{ 6 * $scale }}pt;">REPUBLIC OF THE PHILIPPINES</div>
                        <div style="font-size:{{ 6 * $scale }}pt;">Province of Oriental Mindoro</div>
                        <div style="font-weight:900; font-size:{{ 8 * $scale }}pt;">CITY OF CALAPAN</div>
                    </div>
                </div>

                {{-- ID NUMBER --}}
                <div style="margin-top:{{ 2 * $scale }}mm;">
                    <span style="font-weight:600; font-size:{{ 6 * $scale }}pt;">ID NO.</span>
                    <span style="border-bottom:1px solid #000; padding-left:{{ 1 * $scale }}mm;">{{ $form->pwd_number ?? '—' }}</span>
                </div>

                {{-- NAME + DISABILITY --}}
                <div style="margin-top:{{ 3 * $scale }}mm; text-align:center;">
                    <div style="font-weight:700; font-size:{{ 7.5 * $scale }}pt;">
                        {{ trim(($form->fname ?? '').' '.($form->mname ?? '').' '.($form->lname ?? '').' '.($form->suffix ?? '')) }}
                    </div>
                    <div style="font-size:{{ 6.5 * $scale }}pt; margin-top:{{ 1 * $scale }}mm;">
                        {{ $form->disability_type ?? '—' }}
                    </div>
                </div>

                {{-- SIGNATURE LINE --}}
                <div style="margin-top:auto; text-align:center; font-size:{{ 6 * $scale }}pt;">
                    SIGNATURE / or THUMBMARK
                    <div style="margin-top:{{ 1 * $scale }}mm; font-size:{{ 5.5 * $scale }}pt; color:#444;">
                        The holder of this card is a person with disability. Non-transferable.<br>
                        Valid for 5 years. Any violations is punishable by law.<br>
                        <b>VALID ANYWHERE IN THE COUNTRY</b>
                    </div>
                </div>
            </div>

            {{-- PHOTO --}}
            <div style="width:{{ 22 * $scale }}mm; display:flex; align-items:flex-start; justify-content:center;">
                <div style="width:100%; aspect-ratio:3/4; border-radius:4px; overflow:hidden; background:#eee;">
                    <img src="{{ $photoUrl }}" alt="photo" style="width:100%; height:100%; object-fit:cover;">
                </div>
            </div>
        </div>

    @else
        {{-- BACK SIDE --}}
        <div style="display:flex; flex-direction:column; justify-content:space-between; height:100%;">
            <div>
                <div style="font-weight:700; font-size:{{ 6.5 * $scale }}pt;">ADDRESS</div>
                <div style="border-bottom:1px solid #000; padding-bottom:{{ 0.5 * $scale }}mm; font-size:{{ 6.5 * $scale }}pt;">
                    {{ trim(($form->street ?? '').' '.$form->barangay.' '.$form->municipality.' '.$form->province) }}
                </div>

                <div style="display:flex; justify-content:space-between; margin-top:{{ 2.5 * $scale }}mm;">
                    <div>
                        <div style="font-weight:700;">DATE OF BIRTH</div>
                        <div style="border-bottom:1px solid #000; font-size:{{ 6.5 * $scale }}pt;">
                            {{ $form->birthdate ?? '—' }}
                        </div>
                    </div>
                    <div>
                        <div style="font-weight:700;">DATE ISSUED</div>
                        <div style="border-bottom:1px solid #000; font-size:{{ 6.5 * $scale }}pt;">
                            {{ $form->date_issued ?? '—' }}
                        </div>
                    </div>
                </div>

                <div style="display:flex; justify-content:space-between; margin-top:{{ 2.5 * $scale }}mm;">
                    <div>
                        <div style="font-weight:700;">BLOOD TYPE</div>
                        <div style="border-bottom:1px solid #000; font-size:{{ 6.5 * $scale }}pt;">
                            {{ $form->bloodtype ?? '—' }}
                        </div>
                    </div>
                    <div>
                        <div style="font-weight:700;">SEX</div>
                        <div style="border-bottom:1px solid #000; font-size:{{ 6.5 * $scale }}pt;">
                            {{ strtoupper($form->sex ?? '—') }}
                        </div>
                    </div>
                </div>

                <div style="margin-top:{{ 3 * $scale }}mm;">
                    <div style="font-weight:700;">IN CASE OF EMERGENCY PLEASE NOTIFY :</div>
                    <div style="border-bottom:1px solid #000; height:{{ 4 * $scale }}mm;"></div>
                    <div style="border-bottom:1px solid #000; height:{{ 4 * $scale }}mm; margin-top:{{ 1 * $scale }}mm;"></div>
                </div>
            </div>

            {{-- MAYOR SIGNATURE --}}
            <div style="text-align:right; font-size:{{ 6.5 * $scale }}pt;">
                @if($mayorSignature)
                    <img src="{{ $mayorSignature }}" alt="Mayor Signature"
                        style="width:{{ 18 * $scale }}mm; height:auto; margin-bottom:-{{ 2 * $scale }}mm;">
                @endif
                <div style="font-weight:700;">{{ $mayorName }}</div>
                <div>City Mayor</div>
            </div>
        </div>
    @endif
</div>
