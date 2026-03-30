<div class="us10pr">
    <div class="cstpn">
            <div class="csnm">
                <h2>Users</h2>
            </div>

        </div>
        <div class="ulstlnnm">
            <style>
                .usr-status-badge {
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    padding: 6px 12px;
                    border-radius: 999px;
                    font-weight: 700;
                    font-size: 0.8rem;
                    letter-spacing: 0.2px;
                    border: 1px solid transparent;
                    white-space: nowrap;
                }
                .usr-status-badge::before {
                    content: "";
                    width: 8px;
                    height: 8px;
                    border-radius: 999px;
                    display: inline-block;
                    background: currentColor;
                }
                .usr-status-badge--active { background: #ecfdf5; color: #059669; border-color: #a7f3d0; }
                .usr-status-badge--inactive { background: #f1f5f9; color: #475569; border-color: #e2e8f0; }

                .usr-action-btn {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    gap: 8px;
                    padding: 10px 14px;
                    border-radius: 12px;
                    font-weight: 700;
                    font-size: 0.85rem;
                    border: 1px solid transparent;
                    cursor: pointer;
                    transition: all 0.2s ease;
                    white-space: nowrap;
                    user-select: none;
                }
                .usr-action-btn:focus { outline: none; box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.15); }
                .usr-action-btn--activate { background: #10b981; border-color: #10b981; color: #ffffff; }
                .usr-action-btn--activate:hover { filter: brightness(0.95); box-shadow: 0 8px 18px rgba(16, 185, 129, 0.25); transform: translateY(-1px); }
                .usr-action-btn--deactivate { background: #ffffff; border-color: #ef4444; color: #ef4444; }
                .usr-action-btn--deactivate:hover { background: #fee2e2; box-shadow: 0 8px 18px rgba(239, 68, 68, 0.18); transform: translateY(-1px); }

                .usr-status-overlay {
                    position: fixed; top: 0; left: 0; right: 0; bottom: 0;
                    background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);
                    z-index: 9999; display: none; align-items: center; justify-content: center;
                    opacity: 0; transition: opacity 0.3s ease;
                }
                .usr-status-modal {
                    background: #ffffff; border-radius: 20px; padding: 32px; width: 90%; max-width: 440px;
                    text-align: center; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
                    transform: scale(0.95) translateY(10px); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                }
                .usr-status-overlay.show { display: flex; opacity: 1; }
                .usr-status-overlay.show .usr-status-modal { transform: scale(1) translateY(0); }
                .usm-icon {
                    width: 64px; height: 64px; border-radius: 50%;
                    display: flex; align-items: center; justify-content: center;
                    font-size: 28px; margin: 0 auto 20px;
                    background: #e0e7ff; color: #4f46e5;
                }
                .usm-icon--danger { background: #fee2e2; color: #ef4444; }
                .usm-icon--success { background: #d1fae5; color: #10b981; }
                .usm-title { font-size: 22px; font-weight: 800; color: #0f172a; margin-bottom: 8px; }
                .usm-desc { font-size: 15px; color: #64748b; margin-bottom: 26px; line-height: 1.55; }
                .usm-actions { display: flex; gap: 12px; }
                .usm-btn {
                    flex: 1; padding: 12px; border-radius: 12px; font-size: 15px; font-weight: 800; cursor: pointer; border: none; transition: all 0.2s;
                }
                .usm-cancel { background: #f1f5f9; color: #475569; }
                .usm-cancel:hover { background: #e2e8f0; color: #0f172a; }
                .usm-confirm { background: #4f46e5; color: #ffffff; }
                .usm-confirm:hover { background: #4338ca; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25); }
                .usm-confirm--danger { background: #ef4444; }
                .usm-confirm--danger:hover { background: #dc2626; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.25); }
            </style>

 
            <table>
                <thead>
                    <tr>
                        <th>S. No.</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                @foreach ($usrd as $item)
                @php
                    $statusRaw = strtolower((string) ($item->status ?? ''));
                    $isActive = $statusRaw === 'active';
                    $nextStatus = $isActive ? 'inactive' : 'active';
                @endphp
                <tr>
                    <td>{{$item->S_No}}</td>
                    <td>{{$item->UserName}}</td>
                    <td>{{$item->Email}}</td>
                    <td>{{$item->UserMobile}}</td>
                    <td>
                        @if ($isActive)
                            <span class="usr-status-badge usr-status-badge--active">Active</span>
                        @else
                            <span class="usr-status-badge usr-status-badge--inactive">Deactive</span>
                        @endif
                    </td>
                    <td>
                        <button
                            type="button"
                            class="usr-action-btn {{ $isActive ? 'usr-action-btn--deactivate' : 'usr-action-btn--activate' }} js-user-status-btn"
                            data-user-sno="{{ $item->S_No }}"
                            data-user-name="{{ $item->UserName }}"
                            data-user-email="{{ $item->Email }}"
                            data-current-status="{{ $statusRaw ?: 'inactive' }}"
                            data-next-status="{{ $nextStatus }}"
                        >
                            @if ($isActive)
                                <i class="fa fa-ban" aria-hidden="true"></i> Deactive
                            @else
                                <i class="fa fa-check" aria-hidden="true"></i> Active
                            @endif
                        </button>
                    </td>
                </tr>
                
                @endforeach

            </table>
        <div class="pagin">{{$usrd->links("pagination::bootstrap-4")}}</div>
            
        </div>
    </div>

</div>

<div class="usr-status-overlay" id="userStatusOverlay" aria-hidden="true">
    <div class="usr-status-modal" role="dialog" aria-modal="true" aria-labelledby="userStatusTitle">
        <div class="usm-icon" id="userStatusIcon"><i class="fa fa-user"></i></div>
        <div class="usm-title" id="userStatusTitle">Update User Status</div>
        <div class="usm-desc" id="userStatusDesc">Are you sure?</div>
        <div class="usm-actions">
            <button class="usm-btn usm-cancel" type="button" id="userStatusCancel">Cancel</button>
            <button class="usm-btn usm-confirm" type="button" id="userStatusConfirm">Confirm</button>
        </div>
    </div>
</div>
