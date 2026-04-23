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
           <div class="auth-card" style="margin-bottom: 30px; width: 100%; max-width: 100%;">
                <form method="post" action="{{ route('password.update') }}">
                    @csrf
                    @method('put')

                    <h3 style="margin-bottom: 20px;">🔒 Change Password</h3>

                    <div class="field-group">
                        <label for="current_password">Current Password</label>
                        <input type="password" name="current_password" id="current_password" placeholder="••••••••" required>
                        <button type="button" class="toggle-pw" data-target="current_password" style="position: absolute; right: 10px; background: none; border: none; cursor: pointer; font-size: 1.2rem;">👁</button>
                        @error('current_password', 'updatePassword')
                            <span class="error-msg show">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="field-group">
                            <label for="password">New Password</label>
                            <input type="password" name="password" id="password" placeholder="Min. 8 characters" required>
                            <button type="button" class="toggle-pw" data-target="password" style="position: absolute; right: 10px; background: none; border: none; cursor: pointer; font-size: 1.2rem;">👁</button>
                            @error('password', 'updatePassword')
                                <span class="error-msg show">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="field-group">
                            <label for="password_confirmation">Confirm New Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Repeat new password" required>
                            <button type="button" class="toggle-pw" data-target="password_confirmation" style="position: absolute; right: 10px; background: none; border: none; cursor: pointer; font-size: 1.2rem;">👁</button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Update Password</button>

                    @if (session('status') === 'password-updated')
                        <p style="color: green; font-size: 0.85rem; margin-top: 10px;">Password updated successfully!</p>
                    @endif
                </form>
            </div>
        </div>

        <div class="auth-card danger-zone" style="margin-top: 40px; padding: 25px;">
            <h3 style="color: #ff4d4d; border-bottom-color: #ff4d4d;">🛑 Danger Zone</h3>
            <p style="font-size: 0.9rem; color: #666; margin-bottom: 20px;">
                Once you delete your account, all of your resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.
            </p>

            <form method="post" action="{{ route('profile.destroy') }}" style="display: flex; flex-direction: column; gap: 15px;">
                @csrf
                @method('delete')

                <div class="field-group" style="max-width: 400px;">
                    <label for="password">Confirm Password to Delete</label>
                    <input type="password" name="password" id="password" placeholder="Enter your password" required>
                    @if($errors->userDeletion->has('password'))
                        <span class="error-msg show" style="color: #ff4d4d;">{{ $errors->userDeletion->first('password') }}</span>
                    @endif
                </div>

                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you absolutely sure? This action cannot be undone.')">
                    Permanently Delete Account
                </button>
            </form>
        </div>

    </div>
</div>

<script>

    // Password toggle logic
    document.querySelectorAll('.toggle-pw').forEach(btn => {
        btn.addEventListener('click', () => {
            const targetId = btn.getAttribute('data-target');
            const inp = document.getElementById(targetId);
            
            if (inp.type === 'password') {
                inp.type = 'text';
                btn.textContent = '🙈';
            } else {
                inp.type = 'password';
                btn.textContent = '👁';
            }
        });
    });

    
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