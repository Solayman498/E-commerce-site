@extends('layouts.app')

@section('title', 'Manage Profile & Settings')

@section('content')
<div class="section">
    <div class="container" style="max-width: 900px;">
        
        <div class="page-header" style="margin-bottom: 30px;">
            <h1>⚙️ Manage Profile & Settings</h1>
            <p>Update your personal information, addresses, and security settings.</p>
        </div>

        <div class="auth-card" style="margin-bottom: 30px; width: 100%; max-width: 100%;">
            <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('patch')
                
                <h3 style="margin-bottom: 20px;">👤 Personal Information</h3>
                
                <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 25px; padding-bottom: 25px; border-bottom: 1px solid var(--clr-border);">
                    <div class="profile-avatar" style="width: 80px; height: 80px; font-size: 2rem;">
                        @if(Auth::user()->profile_image)
                            <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" id="avatarPreview" style="width:100%; height:100%; border-radius:50%; object-fit:cover;">
                        @else
                            <span id="avatarText">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        @endif
                    </div>
                    <div>
                        <input type="file" name="profile_image" id="profile_image" style="display:none;" onchange="previewImage(this)">
                        <button type="button" class="btn btn-outline" onclick="document.getElementById('profile_image').click()">Upload New Photo</button>
                        <p style="font-size: 0.75rem; color: var(--clr-text-muted); margin-top: 5px;">Allowed JPG or PNG. Max size 2MB.</p>
                    </div>
                </div>

                <div class="form-row">
                    <div class="field-group">
                        <label>Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="field-group">
                        <label>Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="field-group">
                        <label>Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+880 1XXX XXXXXX">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Save Personal Info</button>
            </form>
        </div>

        <div class="auth-card" style="margin-bottom: 30px; width: 100%; max-width: 100%;">
            <form method="post" action="{{ route('profile.update') }}">
                @csrf
                @method('patch')
                <h3 style="margin-bottom: 20px;">📍 Shipping Address</h3>
                
                <div class="field-group">
                    <label>Street Address</label>
                    <textarea name="address" rows="2" placeholder="House#, Road#, Area...">{{ old('address', $user->address) }}</textarea>
                </div>

                <div class="form-row">
                    <div class="field-group">
                        <label>City</label>
                        <input type="text" name="city" value="{{ old('city', $user->city) }}" placeholder="e.g. Dhaka">
                    </div>
                    <div class="field-group">
                        <label>Postal Code</label>
                        <input type="text" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}" placeholder="e.g. 1212">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Update Address</button>
            </form>
        </div>

        <div class="auth-card" style="margin-bottom: 30px; width: 100%; max-width: 100%;">
            @include('profile.partials.update-password-form')
        </div>

        <div class="auth-card" style="border: 1px solid #ff4d4d22; width: 100%; max-width: 100%;">
            <h3 style="color: #ff4d4d; margin-bottom: 10px;">🛑 Danger Zone</h3>
            <p style="font-size: 0.85rem; color: var(--clr-text-muted); margin-bottom: 15px;">Once you delete your account, there is no going back. Please be certain.</p>
            @include('profile.partials.delete-user-form')
        </div>

    </div>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                let preview = document.getElementById('avatarPreview');
                if(preview) {
                    preview.src = e.target.result;
                } else {
                    // যদি আগে ছবি না থাকে, টেক্সট সরিয়ে ইমেজ ট্যাগ বসানো
                    document.getElementById('avatarText').innerHTML = `<img src="${e.target.result}" style="width:100%; height:100%; border-radius:50%; object-fit:cover;">`;
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection