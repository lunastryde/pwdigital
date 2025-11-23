@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Facades\DB;

    $file = $form->files ?? null;
    $photoUrl = $file && $file->id_picture
        ? Storage::url($file->id_picture)
        : asset('images/default-photo.png');

    $flag       = asset('images/philippine-flag-small.png');
    $greenLogo = asset('images/calapan_flag.png');
    $blueLogo   = asset('images/pdao_logo.png');

    $mayorName       = $mayorName ?? 'ATTY. DOY C. LEACHON';
    $mayorSignature = isset($mayorSignature) ? asset('storage/'.$mayorSignature) : null;

    $scale = isset($preview) && $preview ? 1.35 : 1;
    $mm = fn($n) => (string)($n * $scale) . 'mm';
    $pt = fn($n) => (string)($n * $scale) . 'pt';
    $guardian = DB::table('form_guardian')
        ->where('applicant_id', $form->applicant_id ?? $form->id ?? null)
        ->first();

    $org = DB::table('form_oi')
        ->where('applicant_id', $form->applicant_id ?? $form->id ?? null)
        ->first();

    $iceName = null;
    $icePhone = null;

    if ($guardian && ($guardian->spouse_guardian_fname || $guardian->spouse_guardian_lname)) {
        $iceName = trim(($guardian->spouse_guardian_fname ?? '').' '.($guardian->spouse_guardian_mname ?? '').' '.($guardian->spouse_guardian_lname ?? ''));
        $icePhone = $guardian->spouse_guardian_contact ?: null;
    }
    if (!$iceName && ($guardian && ($guardian->mother_fname || $guardian->mother_lname))) {
        $iceName = trim(($guardian->mother_fname ?? '').' '.($guardian->mother_mname ?? '').' '.($guardian->mother_lname ?? ''));
    }
    if (!$iceName && ($guardian && ($guardian->father_fname || $guardian->father_lname))) {
        $iceName = trim(($guardian->father_fname ?? '').' '.($guardian->father_mname ?? '').' '.($guardian->father_lname ?? ''));
    }
    if (!$iceName && $org && $org->oi_contactperson) {
        $iceName = $org->oi_contactperson;
        $icePhone = $icePhone ?: ($org->oi_telno ?? null);
    }
    if (!$icePhone) {
        $icePhone = $form->contact_no ?? null;
    }

    $iceNameDisplay   = $iceName  ?: '____________________________';
    $icePhoneDisplay = $icePhone ?: '____________________________';
@endphp

{{-- ===== Card surface (fixed ISO-ID size) ===== --}}
<div style="
    width:{{ $mm(85.6) }};
    height:{{ $mm(54) }};
    background:#fff;
    border-radius:6px;
    box-shadow:0 2px 6px rgba(0,0,0,.15);
    padding:{{ $mm(4) }};
    box-sizing:border-box;
    font-family:Arial, Helvetica, sans-serif;
    overflow:hidden;
">

    @if($side === 'front')
        {{-- ================= FRONT ================= --}}
        <div style="display:flex; flex-direction:column; justify-content:space-between; height:100%;">

            {{-- Header: flag | centered text | logos --}}
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:{{ $mm(1.6) }};">
                <img src="{{ $flag }}" alt="flag" style="height:{{ $mm(9) }};">
                <div style="text-align:center; flex:1; line-height:1.15;">
                    <div style="font-weight:700; font-size:{{ $pt(6) }};">REPUBLIC OF THE PHILIPPINES</div>
                    <div style="font-size:{{ $pt(6) }};">Province of Oriental Mindoro</div>
                    <div style="font-weight:900; font-size:{{ $pt(8) }};">CITY OF CALAPAN</div>
                </div>
                <div style="display:flex; gap:{{ $mm(1.4) }};">
                    <img src="{{ $greenLogo }}" alt="city" style="height:{{ $mm(9) }};">
                    <img src="{{ $blueLogo  }}" alt="pdao" style="height:{{ $mm(9) }};">
                </div>
            </div>

            {{-- Content row (left details + right photo) --}}
            <div style="display:flex; gap:{{ $mm(3) }}; align-items:flex-start; flex:1;">
                {{-- Left details --}}
                <div style="flex:1; min-width:0; display:flex; flex-direction:column;">

                    {{-- ID NO --}}
                    <div style="display:flex; align-items:flex-end; gap:{{ $mm(2) }};">
                        <div style="white-space:nowrap; font-weight:600; font-size:{{ $pt(6) }};">ID NO.</div>
                        <div style="flex:1; border-bottom:1px solid #111; padding-bottom:{{ $mm(.5) }}; font-size:{{ $pt(9) }};">
                            {{ $form->pwd_number ?? '—' }}
                        </div>
                    </div>

                    {{-- NAME (underline above label) --}}
                    <div style="text-align:center; margin-top:{{ $mm(1) }};">
                        <div style="font-weight:700; font-size:{{ $pt(8) }};">
                            {{ trim(($form->fname ?? '').' '.($form->mname ?? '').' '.($form->lname ?? '').' '.($form->suffix ?? '')) }}
                        </div>
                        <div style="border-bottom:1px solid #111; margin:{{ $mm(.6) }} 0;"></div>
                        <div style="font-size:{{ $pt(6) }};">NAME</div>
                    </div>

                    {{-- TYPE OF DISABILITY (underline under the value; label below) --}}
                    <div style="text-align:center; margin-top:{{ $mm(1) }};">
                        <div style="font-weight:700; font-size:{{ $pt(7) }};">
                            {{ $form->disability_type ?? '—' }}
                        </div>
                        <div style="border-bottom:1px solid #111; margin:{{ $mm(.6) }} 0 0;"></div>
                        <div style="font-size:{{ $pt(6) }}; margin-top:{{ $mm(.6) }};">TYPE OF DISABILITY</div>
                        <div style="margin-bottom:{{ $mm(1.2) }};"></div>
                    </div> <div style="margin-bottom: 4px;"> </div>
                    {{-- underline directly under the value --}}
                    <div style="border-bottom:1px solid #111; margin:{{ $mm(.6) }} 0 0;"></div> 
                    {{-- Signature label --}}
                    <div style="text-align:center; font-size:{{ $pt(6) }};">SIGNATURE / or THUMBMARK</div>
                </div>

                {{-- Photo --}}
                <div style="flex:0 0 {{ $mm(24) }}; width:{{ $mm(24) }};">
                    <div style="width:100%; aspect-ratio:4/4; border-radius:4px; overflow:hidden; background:#eee; box-shadow:0 1px 2px rgba(0,0,0,.12) inset;">
                        <img src="{{ $photoUrl }}" alt="photo" style="width:100%; height:100%; object-fit:cover;">
                    </div>
                </div>
            </div>

            {{-- FULL-WIDTH policy text (CENTERED across the card) --}}
            <div style="text-align:center; font-size:{{ $pt(5.5) }}; color:#444; line-height:1.25; margin-top:{{ $mm(1) }};">
                The holder of this card is a person with disability. Non-transferable.<br>
                Valid for 5 years. Any violations is punishable by law.<br>
                <b>VALID ANYWHERE IN THE COUNTRY</b>
            </div>
        </div>

    @else
        {{-- ================= BACK ================= --}}
        <div style="display:flex; flex-direction:column; gap:{{ $mm(2) }}; height:100%;">

            <div>
                <div style="font-weight:700; font-size:{{ $pt(6.5) }};">ADDRESS</div>
                <div style="border-bottom:1px solid #111; padding-bottom:{{ $mm(.6) }}; font-size:{{ $pt(6.5) }};">
                    {{ trim(($form->street ?? '').' '.($form->barangay ?? '').' '.($form->municipality ?? '').' '.($form->province ?? '')) }}
                </div>
            </div>

            <div style="display:flex; gap:{{ $mm(3.2) }};">
                <div style="flex:1;">
                    <div style="font-weight:700; font-size:{{ $pt(6) }};">DATE OF BIRTH</div>
                    <div style="border-bottom:1px solid #111; font-size:{{ $pt(6.5) }};">
                        {{ $form->birthdate ?? '—' }}
                    </div>
                </div>
                <div style="flex:1;">
                    <div style="font-weight:700; font-size:{{ $pt(6) }};">DATE ISSUED</div>
                    <div style="border-bottom:1px solid #111; font-size:{{ $pt(6.5) }};">
                        {{ $form->date_issued ?? '—' }}
                    </div>
                </div>
            </div>

            <div style="display:flex; gap:{{ $mm(3.2) }};">
                <div style="flex:1;">
                    <div style="font-weight:700; font-size:{{ $pt(6) }};">BLOOD TYPE</div>
                    <div style="border-bottom:1px solid #111; font-size:{{ $pt(6.5) }};">
                        {{ $form->blood_type ?? '—' }}
                    </div>
                </div>
                <div style="flex:1;">
                    <div style="display:flex; align-items:flex-end; gap:{{ $mm(2) }};">
                        <div style="font-weight:700; font-size:{{ $pt(6) }};">SEX</div>
                        <div style="flex:1; border-bottom:1px solid #111; font-size:{{ $pt(6.5) }};">
                            {{ strtoupper($form->sex ?? '—') }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Emergency Contact --}}
            <div>
                <div style="font-weight:700; font-size:{{ $pt(6) }};">IN CASE OF EMERGENCY PLEASE NOTIFY :</div>

                <div style="display:flex; align-items:flex-end; gap:{{ $mm(2) }}; margin-top:{{ $mm(.6) }};">
                    <div style="white-space:nowrap; font-size:{{ $pt(6.5) }};">Parent/Guardian</div>
                    <div style="flex:1; border-bottom:1px solid #111; padding-bottom:{{ $mm(.8) }}; font-size:{{ $pt(6.5) }};">
                        {{ $iceNameDisplay }}
                    </div>
                </div>

                <div style="display:flex; align-items:flex-end; gap:{{ $mm(2) }}; margin-top:{{ $mm(.8) }};">
                    <div style="white-space:nowrap; font-size:{{ $pt(6.5) }};">Contact No.</div>
                    <div style="flex:1; border-bottom:1px solid #111; padding-bottom:{{ $mm(.8) }}; font-size:{{ $pt(6.5) }};">
                        {{ $icePhoneDisplay }}
                    </div>
                </div>
            </div><div style="margin-bottom: 1px;"> </div>

            {{-- Mayor signature --}}
            <div style="text-align:right; font-size:{{ $pt(5.0) }}; margin-top:auto;">
                @if($mayorSignature)
                    <img src="{{ $mayorSignature }}" alt="Mayor Signature"
                        style="width:{{ $mm(18) }}; height:auto; margin-left:auto; margin-bottom:-{{ $mm(2) }};">
                @endif
                <div style="text-align:center; font-weight:700;">{{ $mayorName }}</div>
                <div style="text-align:center;">City Mayor</div>
            </div>
        </div>
    @endif
</div>