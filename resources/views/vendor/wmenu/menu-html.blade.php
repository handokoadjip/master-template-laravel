<?php
$currentUrl = url()->current();
?>
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<link href="{{asset('vendor/harimayco-menu/style.css')}}" rel="stylesheet">
<div id="hwpwrap">
    <div class="custom-wp-admin wp-admin wp-core-ui js   menu-max-depth-0 nav-menus-php auto-fold admin-bar">
        <div id="wpwrap">
            <div id="wpcontent">
                <div id="wpbody">
                    <div id="wpbody-content">

                        <div class="wrap">

                            <div class="manage-menus">
                                <form method="get" action="{{ $currentUrl }}">
                                    <label for="menu" class="selected-menu">Pilih menu yang ingin diubah:</label>

                                    {!! Menu::select('menu', $menulist) !!}

                                    <span class="submit-btn">
                                        <input type="submit" class="button-secondary" value="Pilih">
                                    </span>
                                    <span class="add-new-menu-action"> atau <a href="{{ $currentUrl }}?action=edit&menu=0">Buat menu baru</a>. </span>
                                </form>
                            </div>
                            <div id="nav-menus-frame">

                                @if(request()->has('menu') && !empty(request()->input("menu")))
                                <div id="menu-settings-column" class="metabox-holder">

                                    <div class="clear"></div>

                                    <form id="nav-menu-meta" action="" class="nav-menu-meta" method="post" enctype="multipart/form-data">
                                        <div id="side-sortables" class="accordion-container">
                                            <ul class="outer-border">
                                                <li class="control-section accordion-section  open add-page" id="add-page">
                                                    <h3 class="accordion-section-title hndle" tabindex="0"> Tautan <span class="screen-reader-text">Tekan icon atau enter untuk meluaskan</span></h3>
                                                    <div class="accordion-section-content ">
                                                        <div class="inside">
                                                            <div class="customlinkdiv" id="customlinkdiv">
                                                                <p id="menu-item-name-wrap">
                                                                    <label class="howto" for="custom-menu-item-name"> <span>Label</span>&nbsp;</label>
                                                                    <input id="custom-menu-item-name" name="label" type="text" class="regular-text menu-item-textbox input-with-default-title" title="Label menu">
                                                                </p>

                                                                <p id="menu-item-url-wrap">
                                                                    <label class="howto" for="custom-menu-item-url"> <span>URL</span>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                                                    <input id="custom-menu-item-url" name="url" type="text" class="menu-item-textbox " placeholder="url">
                                                                </p>

                                                                <p id="menu-item-icon-wrap">
                                                                    <label class="howto" for="custom-menu-item-icon"> <span>Icon</span>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                                                    <input id="custom-menu-item-icon" name="icon" type="text" class="menu-item-textbox icp-auto" placeholder="icon" value="fas fa-plane" id="menuIkon">
                                                                </p>

                                                                @if(!empty($roles))
                                                                <p id="menu-item-role_id-wrap">
                                                                    <label class="howto" for="custom-menu-item-name"> <span>Role</span>&nbsp;
                                                                        <select id="custom-menu-item-role" name="role">
                                                                            <option value="0">Select Role</option>
                                                                            @foreach($roles as $role)
                                                                            <option value="{{ $role->$role_pk }}">{{ ucfirst($role->$role_title_field) }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </label>
                                                                </p>
                                                                @endif

                                                                <p class="button-controls">

                                                                    <a href="#" onclick="addcustommenu()" class="button-secondary submit-add-to-menu right">Tambahkan item menu</a>
                                                                    <span class="spinner" id="spincustomu"></span>
                                                                </p>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>

                                            </ul>
                                        </div>
                                    </form>

                                </div>
                                @endif
                                <div id="menu-management-liquid">
                                    <div id="menu-management">
                                        <form id="update-nav-menu" action="" method="post" enctype="multipart/form-data">
                                            <div class="menu-edit ">
                                                <div id="nav-menu-header">
                                                    <div class="major-publishing-actions">
                                                        <label class="menu-name-label howto open-label" for="menu-name"> <span>Name</span>
                                                            <input name="menu-name" id="menu-name" type="text" class="menu-name regular-text menu-item-textbox" title="Enter menu name" value="@if(isset($indmenu)){{$indmenu->menu_nama}}@endif">
                                                            <input type="hidden" id="idmenu" value="@if(isset($indmenu)){{$indmenu->menu_id}}@endif" />
                                                        </label>

                                                        @if(request()->has('action'))
                                                        <div class="publishing-action">
                                                            <a onclick="createnewmenu()" name="save_menu" id="save_menu_header" class="button button-primary menu-save">Buat menu</a>
                                                        </div>
                                                        @elseif(request()->has("menu"))
                                                        <div class="publishing-action">
                                                            <a onclick="getsave()" name="save_menu" id="save_menu_header" class="button button-primary menu-save">Simpan menu</a>
                                                            <a class="button button-primary" href="{{ $currentUrl . '?menu=' .Request::input('menu')}}"><i class="fa fa-refresh" aria-hidden="true"></i></a>
                                                            <span class="spinner" id="spincustomu2"></span>
                                                        </div>

                                                        @else
                                                        <div class="publishing-action">
                                                            <a onclick="createnewmenu()" name="save_menu" id="save_menu_header" class="button button-primary menu-save">Buat menu</a>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div id="post-body">
                                                    <div id="post-body-content">

                                                        @if(request()->has("menu"))
                                                        <h3>Struktur Menu</h3>
                                                        <div class="drag-instructions post-body-plain">
                                                            <p>
                                                                Tempatkan setiap item dalam urutan yang Anda inginkan. Klik panah di sebelah kanan item untuk menampilkan lebih banyak opsi konfigurasi.
                                                            </p>
                                                        </div>

                                                        @else
                                                        <h3>Pembuatan Menu</h3>
                                                        <div class="drag-instructions post-body-plain">
                                                            <p>
                                                                Masukkan nama dan pilih tombol "Buat menu".
                                                            </p>
                                                        </div>
                                                        @endif

                                                        <ul class="menu ui-sortable" id="menu-to-edit">
                                                            @if(isset($menus))
                                                            @foreach($menus as $m)
                                                            <li id="menu-item|{{$m->menu_item_id}}" class="menu-item menu-item-depth-{{$m->menu_item_kedalaman}} menu-item-page menu-item-edit-inactive pending" style="display: list-item;">
                                                                <dl class="menu-item-bar">
                                                                    <dt class="menu-item-handle">
                                                                        <span class="item-title"> <span class="menu-item-title"> <span id="menutitletemp_{{$m->menu_item_id}}">{{$m->menu_item_label}}</span> <span style="color: transparent; display: none;">|{{$m->menu_item_id}}|</span> </span> <span class="is-submenu" style="@if($m->menu_item_kedalaman==0)display: none;@endif"></span> </span>
                                                                        <span class="item-controls"> <span class="item-type">Link</span> <span class="item-order hide-if-js"> <a href="{{ $currentUrl }}?action=move-up-menu-item&menu-item={{$m->menu_item_id}}&_wpnonce=8b3eb7ac44" class="item-move-up"><abbr title="Move Up">↑</abbr></a> | <a href="{{ $currentUrl }}?action=move-down-menu-item&menu-item={{$m->menu_item_id}}&_wpnonce=8b3eb7ac44" class="item-move-down"><abbr title="Move Down">↓</abbr></a> </span> <a class="item-edit" id="edit-{{$m->menu_item_id}}" title=" " href="{{ $currentUrl }}?edit-menu-item={{$m->menu_item_id}}#menu-item-settings-{{$m->menu_item_id}}"> </a> </span>
                                                                    </dt>
                                                                </dl>

                                                                <div class="menu-item-settings" id="menu-item-settings-{{$m->menu_item_id}}">
                                                                    <input type="hidden" class="edit-menu-item-id" name="menuid_{{$m->menu_item_id}}" value="{{$m->menu_item_id}}" />
                                                                    <p class="field-css-url description description-wide mb-3">
                                                                        <label for="edit-menu-item-title-{{$m->menu_item_id}}"> Label
                                                                        </label>
                                                                        <br>
                                                                        <input type="text" id="idlabelmenu_{{$m->menu_item_id}}" class="widefat code edit-menu-item-title" name="idlabelmenu_{{$m->menu_item_id}}" value="{{$m->menu_item_label}}">
                                                                    </p>

                                                                    <p class="field-css-url description description-wide mb-3">
                                                                        <label for="edit-menu-item-url-{{$m->menu_item_id}}"> URL
                                                                        </label>
                                                                        <br>
                                                                        <input type="text" id="url_menu_{{$m->menu_item_id}}" class="widefat code edit-menu-item-url" id="url_menu_{{$m->menu_item_id}}" value="{{$m->menu_item_tautan}}">
                                                                    </p>

                                                                    <p class="field-css-url description description-wide mb-3">
                                                                        <label for="edit-menu-item-classes-{{$m->menu_item_id}}"> Icon
                                                                        </label>
                                                                        <br>
                                                                        <input type="text" id="clases_menu_{{$m->menu_item_id}}" class="widefat code edit-menu-item-classes icp-auto" name="clases_menu_{{$m->menu_item_id}}" value="{{$m->menu_item_icon}}">
                                                                    </p>

                                                                    @if(!empty($roles))
                                                                    <p class="field-css-role description description-wide">
                                                                        <label for="edit-menu-item-role-{{$m->menu_item_id}}"> Role
                                                                            <br>
                                                                            <select id="role_menu_{{$m->menu_item_id}}" class="widefat code edit-menu-item-role" name="role_menu_[{{$m->menu_item_id}}]">
                                                                                <option value="0">Select Role</option>
                                                                                @foreach($roles as $role)
                                                                                <option @if($role->id == $m->role_id) selected @endif value="{{ $role->$role_pk }}">{{ ucwords($role->$role_title_field) }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </label>
                                                                    </p>
                                                                    @endif

                                                                    <!-- <p class="field-move hide-if-no-js description description-wide">
                                                                        <label> <span>Move</span> <a href="{{ $currentUrl }}" class="menus-move-up" style="display: none;">Move up</a> <a href="{{ $currentUrl }}" class="menus-move-down" title="Mover uno abajo" style="display: inline;">Move Down</a> <a href="{{ $currentUrl }}" class="menus-move-left" style="display: none;"></a> <a href="{{ $currentUrl }}" class="menus-move-right" style="display: none;"></a> <a href="{{ $currentUrl }}" class="menus-move-top" style="display: none;">Top</a> </label>
                                                                    </p> -->

                                                                    <div class="menu-item-actions description-wide submitbox">

                                                                        <a class="button button-danger item-delete me-2 submitdelete deletion" id="delete|{{$m->menu_item_id}}" href="{{ $currentUrl }}?action=delete-menu-item&menu-item={{$m->menu_item_id}}&_wpnonce=2844002501">Hapus</a>
                                                                        <!-- <span class="meta-sep hide-if-no-js"> | </span>
                                                                        <a class="item-cancel submitcancel hide-if-no-js button-secondary" id="cancel-{{$m->menu_item_id}}" href="{{ $currentUrl }}?edit-menu-item={{$m->menu_item_id}}&cancel=1424297719#menu-item-settings-{{$m->menu_item_id}}">Cancel</a>
                                                                        <span class="meta-sep hide-if-no-js"> | </span> -->
                                                                        <a onclick="getmenus()" class="button button-primary updatemenu" id="update-{{$m->menu_item_id}}" href="javascript:void(0)">Ubah</a>

                                                                    </div>

                                                                </div>
                                                                <ul class="menu-item-transport"></ul>
                                                            </li>
                                                            @endforeach
                                                            @endif
                                                        </ul>
                                                        <div class="menu-settings">

                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="nav-menu-footer">
                                                    <div class="major-publishing-actions">

                                                        @if(request()->has('action'))
                                                        <div class="publishing-action">
                                                            <a onclick="createnewmenu()" name="save_menu" id="save_menu_header" class="button button-primary menu-save">Buat menu</a>
                                                        </div>
                                                        @elseif(request()->has("menu"))
                                                        <span class="delete-action"> <a class="submitdelete deletion menu-delete" onclick="deletemenu()" href="javascript:void(9)">Hapus menu</a> </span>
                                                        <div class="publishing-action">

                                                            <a onclick="getsave()" name="save_menu" id="save_menu_header" class="button button-primary menu-save">Simpan menu</a>
                                                            <a class="button button-primary" href="{{ $currentUrl . '?menu=' .Request::input('menu')}}"><i class="fa fa-refresh" aria-hidden="true"></i></a>
                                                            <span class="spinner" id="spincustomu2"></span>
                                                        </div>

                                                        @else
                                                        <div class="publishing-action">
                                                            <a onclick="createnewmenu()" name="save_menu" id="save_menu_header" class="button button-primary menu-save">Buat menu</a>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="clear"></div>
                    </div>

                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
            </div>

            <div class="clear"></div>
        </div>
    </div>
</div>