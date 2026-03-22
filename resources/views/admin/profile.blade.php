<div class="us10pr">
    <div class="cstpn">
        <div class="csnm">
            <h2>Admin Profile</h2>
        </div>
    </div>

    <div style="display:flex; gap:16px; flex-wrap:wrap; margin-top: 14px;">
        <div style="flex: 1; min-width: 320px; background:#fff; border:1px solid #e2e8f0; border-radius: 12px; padding: 16px;">
            <div style="display:flex; align-items:center; justify-content:space-between; gap:12px;">
                <div>
                    <div style="font-size: 12px; font-weight: 800; color:#64748b; text-transform: uppercase; letter-spacing: 0.4px;">
                        Logged in as
                    </div>
                    <div style="font-size: 18px; font-weight: 900; color:#0f172a; margin-top: 6px;">
                        {{ session('admin_username') ?? 'Admin' }}
                    </div>
                    <div style="color:#64748b; font-weight: 700; margin-top: 4px;">
                        This admin account is configured in server settings.
                    </div>
                </div>
                <div style="width: 56px; height: 56px; border-radius: 18px; background: #f1f5f9; display:grid; place-items:center; border:1px solid #e2e8f0;">
                    <i class="fa fa-user" style="font-size: 22px; color:#0f172a;"></i>
                </div>
            </div>

            <hr style="border-color:#e2e8f0; margin: 14px 0;">

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius: 12px; padding: 12px;">
                    <div style="font-size: 12px; font-weight: 800; color:#64748b;">Role</div>
                    <div style="font-weight: 900; color:#0f172a; margin-top: 4px;">Administrator</div>
                </div>
                <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius: 12px; padding: 12px;">
                    <div style="font-size: 12px; font-weight: 800; color:#64748b;">Session</div>
                    <div style="font-weight: 900; color:#0f172a; margin-top: 4px;">Active</div>
                </div>
            </div>

            <div style="margin-top: 14px; display:flex; gap:10px; flex-wrap:wrap;">
                <button type="button" onclick="showLogoutDialog()" style="background:#ef4444; border:0; color:#fff; font-weight:900; padding: 10px 14px; border-radius: 10px;">
                    <i class="fa fa-sign-out"></i> Logout
                </button>
                <a href="/" target="_blank" rel="noreferrer" style="background:#f1f5f9; border:1px solid #e2e8f0; color:#0f172a; font-weight:900; padding: 10px 14px; border-radius: 10px; text-decoration:none;">
                    <i class="fa fa-external-link"></i> View site
                </a>
            </div>
        </div>

        <div style="flex: 1; min-width: 320px; background:#fff; border:1px solid #e2e8f0; border-radius: 12px; padding: 16px;">
            <div style="font-weight: 900; color:#0f172a; font-size: 16px;">Tips</div>
            <div style="color:#64748b; font-weight: 700; margin-top: 6px;">
                For security, admin credentials are not editable from this UI. Update them in server config/environment.
            </div>
            <ul style="margin-top: 12px; color:#0f172a;">
                <li style="margin-bottom: 6px;">Use the sidebar to manage stories, books, orders, and comments.</li>
                <li style="margin-bottom: 6px;">Use "Logout" after finishing admin work on public/shared computers.</li>
            </ul>
        </div>
    </div>
</div>

