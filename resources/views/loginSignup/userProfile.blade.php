@php
    $css = ['demonav', 'footer', 'prplc', 'userprf'];
    $nav = ['commentcn', 'navbar', 'chpck45usr', 'sbs'];
@endphp

<x-navbar :nav="$nav" :css="$css" desc="Rudra Stories || à¤°à¥à¤¦à¥à¤° à¤•à¥€ à¤•à¤¹à¤¾à¤¨à¤¿à¤¯à¤¾à¤‚"
    key=" Rudra Stories || à¤°à¥à¤¦à¥à¤° à¤•à¥€ à¤•à¤¹à¤¾à¤¨à¤¿à¤¯à¤¾à¤‚ " />

<meta name="csrf_token" content="{{ csrf_token() }}">

<style>
    .up-profile-shell { padding: 26px 18px; max-width: 1150px; margin: 0 auto; }
    .up-hero {
        background: linear-gradient(135deg, rgba(29,53,87,0.10), rgba(230,57,70,0.10));
        border: 1px solid rgba(15,23,42,0.08);
        border-radius: 18px;
        padding: 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 18px;
    }
    .up-hero h1 { margin: 0; font-size: 22px; font-weight: 800; color: #0f172a; }
    .up-hero p { margin: 0; color: #64748b; font-weight: 600; }
    .up-grid { display: grid; grid-template-columns: 360px 1fr; gap: 16px; }
    .up-card {
        background: #fff;
        border: 1px solid rgba(15,23,42,0.08);
        border-radius: 18px;
        box-shadow: 0 18px 40px rgba(2,6,23,0.06);
        overflow: hidden;
    }
    .up-card .up-card-h {
        padding: 14px 16px;
        border-bottom: 1px solid rgba(15,23,42,0.08);
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap: 10px;
    }
    .up-card .up-card-h .t { font-weight: 900; color:#0f172a; }
    .up-card .up-card-b { padding: 16px; }

    .up-avatar {
        width: 96px; height: 96px; border-radius: 22px; object-fit: cover;
        border: 3px solid rgba(226,232,240,0.9);
        box-shadow: 0 14px 30px rgba(2,6,23,0.10);
    }
    .up-identity { display:flex; align-items:center; gap: 14px; }
    .up-name { font-size: 18px; font-weight: 900; margin: 0; color:#0f172a; }
    .up-meta { margin: 0; color:#64748b; font-weight: 700; font-size: 13px; }

    .up-btn {
        border: 0; border-radius: 12px; padding: 10px 12px; font-weight: 800;
        background: #e63946; color: #fff; cursor: pointer;
    }
    .up-btn:hover { background: #d62828; color: #fff; }
    .up-btn-light {
        background: #f1f5f9; color: #0f172a; border: 1px solid rgba(15,23,42,0.08);
    }
    .up-btn-light:hover { background: #e2e8f0; color: #0f172a; }

    .up-row { display:grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .up-field { padding: 12px; border-radius: 16px; background: #f8fafc; border: 1px solid rgba(15,23,42,0.06); }
    .up-field .k { color: #64748b; font-weight: 800; font-size: 12px; text-transform: uppercase; letter-spacing: 0.4px; margin-bottom: 4px; }
    .up-field .v { color: #0f172a; font-weight: 800; }

    .up-actions { display:flex; align-items:center; justify-content:space-between; gap: 10px; flex-wrap: wrap; }

    /* Upload modal (keep `.content` + `.clos` hooks for existing JS) */
    .up-upload-overlay {
        position: fixed; inset: 0;
        background: rgba(15,23,42,0.65);
        backdrop-filter: blur(8px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }
    .content {
        width: min(560px, 92vw);
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 25px 70px rgba(2,6,23,0.35);
        padding: 18px;
        position: relative;
        display: none;
    }
    .close-btn {
        position: absolute; top: 10px; right: 12px;
        width: 36px; height: 36px; border-radius: 12px;
        display: grid; place-items: center;
        background: #f1f5f9; color: #0f172a; cursor: pointer; font-weight: 900;
    }
    .close-btn:hover { background: #e2e8f0; }
    .pckpup h1 { font-size: 18px; font-weight: 900; margin: 0 0 10px; color: #0f172a; }
    .uplnd { margin-top: 10px; width: 100%; padding: 12px; border-radius: 12px; border: 0; background: #e63946; color: #fff; font-weight: 900; }
    .uplnd:hover { background: #d62828; }

    @media (max-width: 980px) { .up-grid { grid-template-columns: 1fr; } }
</style>

<div class="up-profile-shell">
    <div class="up-hero">
        <div>
            <h1>My Profile</h1>
            <p>Manage your account details and notifications.</p>
        </div>
        <div class="up-actions">
            <button class="up-btn up-btn-light clos" type="button">
                <i class="fa fa-camera"></i> Change photo
            </button>
        </div>
    </div>

    <div class="up-grid">
        <div class="up-card">
            <div class="up-card-h">
                <div class="t">Account</div>
            </div>
            <div class="up-card-b">
                @foreach ($data as $item)
                    <div class="up-identity">
                        @if ($item->images != null)
                            <img src="{{ asset('userProfile/' . $item->images) }}" class="up-avatar" alt="User-Profile-Image">
                        @else
                            <img src="{{ asset('images/usericon.png') }}" class="up-avatar" alt="User-Profile-Image">
                        @endif
                        <div>
                            <p class="up-name">{{ $item->UserName }}</p>
                            <p class="up-meta">{{ $item->Email }}</p>
                        </div>
                    </div>

                    <hr style="border-color: rgba(15,23,42,0.08); margin: 16px 0;">

                    <div class="up-row">
                        <div class="up-field">
                            <div class="k">Username</div>
                            <div class="v">{{ $item->UserName }}</div>
                        </div>
                        <div class="up-field">
                            <div class="k">Phone</div>
                            <div class="v">{{ $item->UserMobile }}</div>
                        </div>
                    </div>

                    <div class="mt-3 up-field">
                        <div class="k">Email notifications</div>
                        <div style="color:#64748b; font-weight:700; font-size: 13px; margin-bottom: 10px;">
                            Get latest news and stories on email.
                        </div>
                        <form action="" method="post" id="sbs045" style="display:flex; gap: 10px; align-items:center; flex-wrap: wrap;">
                            <input type="hidden" name="susuid" value="{{ $item->Email }}">
                            @csrf
                            <input type="submit" class="up-btn" id="chksum" value="{{ $subs }}" style="min-width: 180px;" />
                            <div style="color:#94a3b8; font-weight:700; font-size: 12px;">
                                You can unsubscribe anytime.
                            </div>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="up-card">
            <div class="up-card-h">
                <div class="t">Latest Stories</div>
                <a href="/all_stories" class="up-btn up-btn-light" style="text-decoration:none;">
                    <i class="fa fa-book"></i> View all
                </a>
            </div>
            <div class="up-card-b">
                <div class="topstry">
                    @foreach ($latest as $item)
                        <div class="allstryshow" data-aos="fade-up" data-aos-anchor-placement="center-bottom">
                            <div class="stry1">
                                <img src="{{ asset('storyImages/' . $item->images) }}" alt="">
                            </div>
                            <div class="stry1">
                                <div class="stryno1">
                                    <h2>{{ $item->story_heading }}</h2>
                                    <p>{{ Str::substr($item->story_desc, 0, 70) }}</p>
                                    <div class="stbtn">
                                        <a href="/stories/{{ $item->story_id }}/{{ $item->story_identy }} " id="morebtn" target="_blank">Read more</a>
                                    </div>
                                    <div class="stinf">
                                        <i class="fa fa-thumbs-o-up">&nbsp;<span>{{ $item->stry_likes }}</span></i>
                                        <i class="fa fa-eye">&nbsp;<span>{{ $item->view }}</span></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div class="up-upload-overlay" id="upUploadOverlay">
    <div class="content" id="upUploadCard">
        <div class="close-btn clos" type="button">×</div>

        <form action="" method="post" id="frmim00" enctype="multipart/form-data">
            <div class="pckpup">
                <h1>Upload Profile Photo</h1>
                <input type="file" id="file" name="usor4fg" />
                <label for="file">Choose a file</label>

                @csrf
                <input type="submit" class="uplnd" value="Upload Image" name="submit">
            </div>
            <div class="mt-3">
                <div class="alert alert-danger" style="border-radius: 14px;">
                    <h3 id="cerr" style="margin:0; font-size: 14px; font-weight: 800;"></h3>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    (function () {
        var overlay = document.getElementById('upUploadOverlay');
        var card = document.getElementById('upUploadCard');
        if (!overlay || !card) return;

        function syncVisibility() {
            var display = window.getComputedStyle(card).display;
            overlay.style.display = (display !== 'none') ? 'flex' : 'none';
        }

        var observer = new MutationObserver(syncVisibility);
        observer.observe(card, { attributes: true, attributeFilter: ['style', 'class'] });
        syncVisibility();

        overlay.addEventListener('click', function (e) {
            if (e.target === overlay) {
                var closers = document.getElementsByClassName('clos');
                if (closers && closers.length > 0) closers[0].click();
            }
        });
    })();
</script>

<x-footer />
