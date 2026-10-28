/**
 * Ardal — مدیریت پیشرفته فهرست‌ها و مگا منو در پیشخوان وردپرس.
 *
 * ۱. سازمان‌دهی و جابجایی دسته‌جمعی (Bulk Organizer) با چک‌باکس، Shift+Click و انتقال گروهی.
 * ۲. پایش مصرف max_input_vars سرور.
 * ۳. افزودن درختی دسته‌بندی‌های ووکامرس (Smart Tree Importer).
 * ۴. کنترل گزینه‌های مگامنو و تزریق خودکار زیردسته‌ها (Dynamic Taxonomy).
 */
( function ( $ ) {
	'use strict';

	var frames = {};
	var data = window.ardalMegaMenuData || {};
	var i18n = data.i18n || {};
	var lastCheckedIndex = null;

	/* -------------------------------------------------------------------------
	 * ۱. هماهنگی مگامنو و گزینه‌های داینامیک در فرم تنظیمات هر آیتم
	 * ------------------------------------------------------------------------- */

	function syncMega( $box ) {
		var $check = $box.find( '[data-ardal-mm-mega]' );
		$box.toggleClass( 'is-mega', $check.length > 0 && $check.is( ':checked' ) );
	}

	function syncDynamic( $box ) {
		var $check = $box.find( '[data-ardal-mm-dynamic]' );
		var $options = $box.find( '[data-ardal-mm-dyn-options]' );

		if ( $check.length > 0 && $check.is( ':checked' ) ) {
			$options.slideDown( 180 );
			$box.addClass( 'is-dyn-active' );
		} else {
			$options.slideUp( 140 );
			$box.removeClass( 'is-dyn-active' );
		}
	}

	function syncAllFields() {
		$( '[data-ardal-mm-fields]' ).each( function () {
			var $box = $( this );
			syncMega( $box );
			syncDynamic( $box );
		} );
	}

	/* -------------------------------------------------------------------------
	 * ۲. ابزار سازمان‌دهی و جابجایی دسته‌جمعی (Bulk Organizer Toolbar)
	 * ------------------------------------------------------------------------- */

	function getItemDepth( $item ) {
		var match = ( $item.attr( 'class' ) || '' ).match( /menu-item-depth-(\d+)/ );
		return match ? parseInt( match[1], 10 ) : 0;
	}

	function setItemDepth( $item, newDepth ) {
		var curDepth = getItemDepth( $item );
		$item.removeClass( 'menu-item-depth-' + curDepth ).addClass( 'menu-item-depth-' + newDepth );
	}

	function getMenuItemId( $item ) {
		var idStr = $item.attr( 'id' ) || '';
		return parseInt( idStr.replace( 'menu-item-', '' ), 10 ) || 0;
	}

	function getMenuItemTitle( $item ) {
		var title = $item.find( '.menu-item-title' ).first().text().trim();
		if ( ! title ) {
			title = $item.find( '.item-title' ).first().text().trim();
		}
		return title || ( '#' + getMenuItemId( $item ) );
	}

	/**
	 * بازسازی و اعتبارسنجی بنیادین ساختار درختی منو:
	 * ۱. تراز عمق‌ها (هیچ آیتمی نمی‌تواند بیش از ۱ سطح نسبت به آیتم قبلی عمیق‌تر باشد).
	 * ۲. پیدا کردن و تنظیم دقیق menu-item-data-parent-id بر اساس چیدمان واقعی DOM.
	 * ۳. شماره‌گذاری دقیق موقعیت‌ها (menu-item-data-position: 1..N).
	 * ۴. فعال‌سازی وضعیت تغییر در وردپرس جهت ذخیره مطمئن در پایگاه داده.
	 */
	function refreshMenuTreeAndParents() {
		var $items = $( '#menu-to-edit > li.menu-item' );
		if ( ! $items.length ) {
			return;
		}

		var prevDepth = -1;

		$items.each( function ( index ) {
			var $item = $( this );
			var curDepth = getItemDepth( $item );

			// قاعده وردپرس: آیتم اول منو همواره در سطح ۰ (بدون والد) است
			if ( 0 === index ) {
				curDepth = 0;
			} else {
				// هیچ آیتمی نباید بیش از ۱ سطح نسبت به آیتم ماقبل خود فاصله داشته باشد
				if ( curDepth > prevDepth + 1 ) {
					curDepth = prevDepth + 1;
				}
			}

			curDepth = Math.max( 0, Math.min( 11, curDepth ) );
			setItemDepth( $item, curDepth );
			prevDepth = curDepth;

			// محاسبه دقیق شناسه والد:
			// در عمق ۰ والد صفر است.
			// در عمق بالای صفر، نزدیک‌ترین آیتم ماقبل با عمق (curDepth - 1) والد قطعی است.
			var parentId = 0;
			if ( curDepth > 0 ) {
				var $prevAll = $item.prevAll( 'li.menu-item' );
				$prevAll.each( function () {
					var $prev = $( this );
					if ( getItemDepth( $prev ) === curDepth - 1 ) {
						parentId = getMenuItemId( $prev );
						return false; // شکستن حلقه
					}
				} );
			}

			// تنظیم قطعی فیلدهای پنهان فرم وردپرس که مستقیماً در POST ذخیره می‌شوند
			$item.find( '.menu-item-data-parent-id' ).val( parentId );
			$item.find( '.menu-item-data-position' ).val( index + 1 );
		} );

		markMenuDirty();
		updateVarsCounter();
		updateParentOptions();
	}

	function initBulkToolbar() {
		var $menuList = $( '#menu-to-edit' );
		if ( ! $menuList.length || $( '#ardal-bulk-bar' ).length ) {
			return;
		}

		var toolbarHtml = [
			'<div id="ardal-bulk-bar" class="ardal-bulk-bar">',
				'<div class="ardal-bulk-bar__header">',
					'<div class="ardal-bulk-bar__title">',
						'<span class="ardal-bulk-bar__mark"></span>',
						'<strong>' + ( i18n.bulkToolbarTitle || 'مدیریت و چیدمان دسته‌جمعی (اردال)' ) + '</strong>',
						'<span class="ardal-bulk-tip">💡 امکان درگ‌ودراپ هم‌زمان تمام آیتم‌های انتخاب‌شده</span>',
					'</div>',
					'<div class="ardal-bulk-bar__vars" data-ardal-vars-badge title="پایش تعداد فیلدهای فرم جهت جلوگیری از محدودیت سرور">',
						'<span class="ardal-vars-dot"></span>',
						'<span class="ardal-vars-text">...</span>',
					'</div>',
				'</div>',
				'<div class="ardal-bulk-bar__controls">',
					'<div class="ardal-bulk-bar__group ardal-bulk-bar__select-group">',
						'<label class="ardal-bulk-pill" title="انتخاب یا لغو همه موارد">',
							'<input type="checkbox" id="ardal-select-all-cb">',
							'<span>' + ( i18n.selectAll || 'انتخاب همه' ) + '</span>',
						'</label>',
						'<span class="ardal-bulk-count" data-ardal-bulk-count>۰ ' + ( i18n.selectedCount || 'مورد انتخاب شده' ) + '</span>',
					'</div>',
					'<div class="ardal-bulk-bar__group ardal-bulk-bar__action-group">',
						'<span class="ardal-bulk-label">انتقال به زیردستهٔ:</span>',
						'<select id="ardal-bulk-parent-select" class="ardal-bulk-select">',
							'<option value="0">' + ( i18n.rootItem || 'سطح اصلی فهرست (بدون والد)' ) + '</option>',
						'</select>',
						'<button type="button" class="button button-primary ardal-btn-move" id="ardal-btn-move" disabled>',
							( i18n.applyMove || 'انتقال گروهی' ),
						'</button>',
						'<span class="ardal-sep"></span>',
						'<button type="button" class="button ardal-btn-shift" id="ardal-btn-indent" title="' + ( i18n.indent || 'یک سطح به داخل' ) + '" disabled>',
							'→ ' + ( i18n.indent || 'به داخل' ),
						'</button>',
						'<button type="button" class="button ardal-btn-shift" id="ardal-btn-outdent" title="' + ( i18n.outdent || 'یک سطح به بیرون' ) + '" disabled>',
							'← ' + ( i18n.outdent || 'به بیرون' ),
						'</button>',
						'<button type="button" class="button button-link-delete ardal-btn-delete" id="ardal-btn-bulk-delete" disabled>',
							( i18n.deleteSelected || 'حذف موارد' ),
						'</button>',
					'</div>',
				'</div>',
			'</div>'
		].join( '' );

		$menuList.before( toolbarHtml );
		refreshItemCheckboxes();
		updateParentOptions();
		updateVarsCounter();
	}

	function refreshItemCheckboxes() {
		var $items = $( '#menu-to-edit > li.menu-item' );

		$items.each( function () {
			var $item = $( this );
			var itemId = getMenuItemId( $item );

			// تزریق چک‌باکس انتخاب کنار عنوان آیتم
			if ( ! $item.find( '.ardal-item-cb-wrap' ).length ) {
				var $handle = $item.find( '.menu-item-handle' ).first();
				var cbWrap = $( '<span class="ardal-item-cb-wrap" title="انتخاب با کلیک یا Shift+Click"><input type="checkbox" class="ardal-item-cb" value="' + itemId + '"></span>' );
				$handle.prepend( cbWrap );
			}

			// بررسی وجود زیرشاخه برای افزودن دکمه انتخاب شاخه
			var curDepth = getItemDepth( $item );
			var $next = $item.next( 'li.menu-item' );
			var hasChildren = $next.length && getItemDepth( $next ) > curDepth;

			if ( hasChildren && ! $item.find( '.ardal-branch-pick' ).length ) {
				var $titleWrap = $item.find( '.item-title' ).first();
				var branchBtn = $( '<button type="button" class="button-link ardal-branch-pick" title="انتخاب تمام زیرشاخه‌های این آیتم">⤹ انتخاب شاخه</button>' );
				$titleWrap.after( branchBtn );
			}
		} );
	}

	function updateSelectedState() {
		var $checked = $( '#menu-to-edit .ardal-item-cb:checked' );
		var count = $checked.length;
		var total = $( '#menu-to-edit .ardal-item-cb' ).length;

		$( '[data-ardal-bulk-count]' ).text( count + ' ' + ( i18n.selectedCount || 'مورد انتخاب شده' ) );

		// هایلایت بصری آیتم‌ها
		$( '#menu-to-edit > li.menu-item' ).each( function () {
			var isChecked = $( this ).find( '.ardal-item-cb' ).is( ':checked' );
			$( this ).toggleClass( 'is-bulk-selected', isChecked );
		} );

		// فعال/غیرفعال کردن دکمه‌های اکشن
		$( '#ardal-btn-move, #ardal-btn-indent, #ardal-btn-outdent, #ardal-btn-bulk-delete' ).prop( 'disabled', count === 0 );

		// وضعیت چک‌باکس انتخاب همه
		var $allCb = $( '#ardal-select-all-cb' );
		if ( count === 0 ) {
			$allCb.prop( 'checked', false ).prop( 'indeterminate', false );
		} else if ( count === total ) {
			$allCb.prop( 'checked', true ).prop( 'indeterminate', false );
		} else {
			$allCb.prop( 'checked', false ).prop( 'indeterminate', true );
		}

		updateParentOptions();
	}

	function updateParentOptions() {
		var $select = $( '#ardal-bulk-parent-select' );
		var $importSelect = $( '#ardal-import-target-select' );
		var currentVal = $select.val();
		var importCurVal = $importSelect.val();
		var selectedItemIds = {};

		$( '#menu-to-edit .ardal-item-cb:checked' ).each( function () {
			selectedItemIds[ $( this ).val() ] = true;
		} );

		var bulkOptions = [ '<option value="0">' + ( i18n.rootItem || 'سطح اصلی فهرست (بدون والد)' ) + '</option>' ];
		var importOptions = [ '<option value="0">' + ( i18n.rootItem || 'سطح اصلی فهرست (بدون والد — انتهای منو)' ) + '</option>' ];

		$( '#menu-to-edit > li.menu-item' ).each( function () {
			var $item = $( this );
			var id = getMenuItemId( $item );
			var depth = getItemDepth( $item );
			var indent = '';
			for ( var i = 0; i < depth; i++ ) {
				indent += '— ';
			}

			var title = getMenuItemTitle( $item );
			var optHtml = '<option value="' + id + '">' + indent + title + '</option>';

			// لیست متاباکس هوشمند شامل تمام آیتم‌هاست
			importOptions.push( optHtml );

			// لیست نوار ابزار نباید شامل خود آیتم‌های انتخاب‌شده باشد
			if ( ! selectedItemIds[ id ] ) {
				bulkOptions.push( optHtml );
			}
		} );

		$select.html( bulkOptions.join( '' ) );
		if ( $select.find( 'option[value="' + currentVal + '"]' ).length ) {
			$select.val( currentVal );
		} else {
			$select.val( '0' );
		}

		if ( $importSelect.length ) {
			$importSelect.html( importOptions.join( '' ) );
			if ( $importSelect.find( 'option[value="' + importCurVal + '"]' ).length ) {
				$importSelect.val( importCurVal );
			} else {
				$importSelect.val( '0' );
			}
		}
	}

	function updateVarsCounter() {
		var $badge = $( '[data-ardal-vars-badge]' );
		if ( ! $badge.length ) {
			return;
		}

		var inputCount = $( '#menu-to-edit input, #menu-to-edit select, #menu-to-edit textarea' ).length;
		var maxVars = data.maxInputVars || 1000;
		var ratio = inputCount / maxVars;

		$badge.removeClass( 'is-safe is-warn is-danger' );

		if ( ratio >= 0.85 ) {
			$badge.addClass( 'is-danger' );
			$badge.find( '.ardal-vars-text' ).text( inputCount + ' / ' + maxVars + ' فیلد (بحرانی!)' );
		} else if ( ratio >= 0.65 ) {
			$badge.addClass( 'is-warn' );
			$badge.find( '.ardal-vars-text' ).text( inputCount + ' / ' + maxVars + ' فیلد (نزدیک به سقف)' );
		} else {
			$badge.addClass( 'is-safe' );
			$badge.find( '.ardal-vars-text' ).text( inputCount + ' / ' + maxVars + ' فیلد (امن)' );
		}
	}

	function notifyUser( msg, isSuccess ) {
		var $toast = $( '#ardal-toast' );
		if ( ! $toast.length ) {
			$toast = $( '<div id="ardal-toast" class="ardal-toast"></div>' );
			$( 'body' ).append( $toast );
		}

		$toast.removeClass( 'is-error is-success' )
			.addClass( isSuccess ? 'is-success' : 'is-error' )
			.text( msg )
			.addClass( 'is-show' );

		window.clearTimeout( $toast.data( 'timer' ) );
		var timer = window.setTimeout( function () {
			$toast.removeClass( 'is-show' );
		}, 3600 );
		$toast.data( 'timer', timer );
	}

	function markMenuDirty() {
		$( '#update-nav-menu' ).find( '.button-primary' ).prop( 'disabled', false );
		if ( window.wpNavMenu && window.wpNavMenu.menuList ) {
			window.wpNavMenu.menuList.sortable( 'refresh' );
		}
	}

	/* -------------------------------------------------------------------------
	 * ۳. عملیات انتقال گروهی به زیر والد مشخص (Bulk Move Under Parent)
	 * ------------------------------------------------------------------------- */

	function bulkMoveItems() {
		var $selectedItems = $( '#menu-to-edit > li.menu-item.is-bulk-selected' );
		if ( ! $selectedItems.length ) {
			return;
		}

		var targetParentId = parseInt( $( '#ardal-bulk-parent-select' ).val(), 10 ) || 0;
		var targetDepth = 0;
		var $insertAfterPoint = null;

		if ( targetParentId > 0 ) {
			var $targetLi = $( '#menu-item-' + targetParentId );
			if ( ! $targetLi.length ) {
				return;
			}
			targetDepth = getItemDepth( $targetLi ) + 1;

			// پیدا کردن آخرین فرزند فعلی والد هدف جهت درج بعد از آن
			$insertAfterPoint = $targetLi;
			var $next = $targetLi.next( 'li.menu-item' );
			while ( $next.length && getItemDepth( $next ) >= targetDepth ) {
				$insertAfterPoint = $next;
				$next = $next.next( 'li.menu-item' );
			}
		} else {
			// والد = ریشه، انتقال به انتهای فهرست
			targetDepth = 0;
			$insertAfterPoint = $( '#menu-to-edit > li.menu-item' ).not( '.is-bulk-selected' ).last();
		}

		// محاسبه کمترین عمق در بین آیتم‌های انتخاب شده برای حفظ نسبت درونی
		var minSelectedDepth = 99;
		$selectedItems.each( function () {
			var d = getItemDepth( $( this ) );
			if ( d < minSelectedDepth ) {
				minSelectedDepth = d;
			}
		} );

		// جابجایی عناصر در DOM با عمق‌های اولیه متناسب
		$selectedItems.each( function () {
			var $item = $( this );
			var curDepth = getItemDepth( $item );
			var relativeOffset = curDepth - minSelectedDepth;
			var newDepth = Math.max( 0, Math.min( 11, targetDepth + relativeOffset ) );

			setItemDepth( $item, newDepth );

			if ( $insertAfterPoint && $insertAfterPoint.length && $insertAfterPoint[0] !== $item[0] ) {
				$item.insertAfter( $insertAfterPoint );
				$insertAfterPoint = $item;
			}
		} );

		// بازسازی و محاسبه ۱۰۰٪ بی‌نقص تمام والدها و موقعیت‌ها در کل DOM
		refreshMenuTreeAndParents();

		var targetName = targetParentId > 0 ? getMenuItemTitle( $( '#menu-item-' + targetParentId ) ) : ( i18n.rootItem || 'سطح اصلی' );
		var msg = ( i18n.movedSuccess || '%s آیتم با موفقیت به زیر «%s» منتقل شدند.' ).replace( '%s', $selectedItems.length ).replace( '%s', targetName );
		notifyUser( msg, true );
	}

	/* -------------------------------------------------------------------------
	 * ۴. عملیات تغییر عمق دسته‌جمعی (Bulk Indent / Outdent)
	 * ------------------------------------------------------------------------- */

	function bulkShiftDepth( delta ) {
		var $selectedItems = $( '#menu-to-edit > li.menu-item.is-bulk-selected' );
		if ( ! $selectedItems.length ) {
			return;
		}

		$selectedItems.each( function () {
			var $item = $( this );
			var curDepth = getItemDepth( $item );
			var newDepth = curDepth + delta;

			if ( newDepth >= 0 && newDepth <= 11 ) {
				setItemDepth( $item, newDepth );
			}
		} );

		// بازسازی مجدد سلسله‌مراتب و والدها
		refreshMenuTreeAndParents();
	}

	/* -------------------------------------------------------------------------
	 * ۵. عملیات حذف دسته‌جمعی (Bulk Delete)
	 * ------------------------------------------------------------------------- */

	function bulkDeleteItems() {
		var $selectedItems = $( '#menu-to-edit > li.menu-item.is-bulk-selected' );
		if ( ! $selectedItems.length ) {
			return;
		}

		var confirmMsg = ( i18n.confirmDelete || 'آیا از حذف %s آیتم انتخاب‌شده از فهرست مطمئن هستید؟' ).replace( '%s', $selectedItems.length );
		if ( ! window.confirm( confirmMsg ) ) {
			return;
		}

		$selectedItems.each( function () {
			var $item = $( this );
			var $delBtn = $item.find( '.item-delete' );
			if ( $delBtn.length ) {
				$delBtn.trigger( 'click' );
			} else {
				$item.remove();
			}
		} );

		markMenuDirty();
		updateVarsCounter();
		updateSelectedState();
	}

	/* -------------------------------------------------------------------------
	 * ۶. متاباکس افزودن هوشمند درختی دسته‌ها (Smart Taxonomy Importer)
	 * ------------------------------------------------------------------------- */

	function initTaxonomyImporter() {
		var $box = $( '[data-ardal-tax-importer]' );
		if ( ! $box.length ) {
			return;
		}

		// فیلتر جستجوی زنده در درخت دسته‌ها
		$box.on( 'input', '[data-ardal-tax-search]', function () {
			var query = $( this ).val().trim().toLowerCase();
			var $nodes = $box.find( '.ardal-tax-node' );

			if ( ! query ) {
				$nodes.show();
				return;
			}

			$nodes.each( function () {
				var text = $( this ).find( '.ardal-tax-name' ).text().toLowerCase();
				$( this ).toggle( text.indexOf( query ) !== -1 );
			} );
		} );

		// انتخاب همه / لغو در متاباکس
		$box.on( 'click', '[data-ardal-tax-select-all]', function () {
			$box.find( '.ardal-tax-node:visible input[type="checkbox"]' ).prop( 'checked', true );
		} );

		$box.on( 'click', '[data-ardal-tax-deselect-all]', function () {
			$box.find( '.ardal-tax-node input[type="checkbox"]' ).prop( 'checked', false );
		} );

		// دکمه شاخه کامل
		$box.on( 'click', '[data-ardal-add-branch]', function ( e ) {
			e.preventDefault();
			var branchId = parseInt( $( this ).data( 'ardal-add-branch' ), 10 );
			if ( branchId > 0 ) {
				importTermsToMenu( { branch_id: branchId } );
			}
		} );

		// دکمه افزودن انتخاب‌شده‌ها
		$box.on( 'click', '[data-ardal-import-selected]', function ( e ) {
			e.preventDefault();
			var termIds = [];
			$box.find( '.ardal-tax-node input[type="checkbox"]:checked' ).each( function () {
				termIds.push( parseInt( $( this ).val(), 10 ) );
			} );

			if ( ! termIds.length ) {
				alert( i18n.noSelection || 'لطفاً ابتدا حداقل یک دسته را انتخاب کنید.' );
				return;
			}

			importTermsToMenu( { term_ids: termIds } );
		} );
	}

	function importTermsToMenu( params ) {
		var menuId = parseInt( $( '#menu' ).val(), 10 );
		if ( ! menuId ) {
			alert( 'لطفاً ابتدا یک فهرست را انتخاب یا ذخیره کنید.' );
			return;
		}

		var $box = $( '[data-ardal-tax-importer]' );
		var $spinner = $box.find( '[data-ardal-import-spinner]' );
		var $btn = $box.find( '[data-ardal-import-selected]' );
		var keepHierarchy = $box.find( '[data-ardal-keep-hierarchy]' ).is( ':checked' ) ? 1 : 0;
		var targetParentId = parseInt( $( '#ardal-import-target-select' ).val(), 10 ) || 0;

		$spinner.addClass( 'is-active' );
		$btn.prop( 'disabled', true );

		var postData = $.extend( {
			action: 'ardal_add_taxonomy_tree_to_menu',
			nonce: data.nonce,
			menu_id: menuId,
			taxonomy: 'product_cat',
			keep_hierarchy: keepHierarchy,
			target_menu_item_id: targetParentId
		}, params );

		$.post( data.ajaxUrl, postData, function ( response ) {
			$spinner.removeClass( 'is-active' );
			$btn.prop( 'disabled', false );

			if ( response && response.success && response.data && response.data.html ) {
				var $newItems = $( response.data.html );
				var targetId = response.data.target_parent_id ? parseInt( response.data.target_parent_id, 10 ) : 0;

				if ( targetId > 0 && $( '#menu-item-' + targetId ).length ) {
					var $targetLi = $( '#menu-item-' + targetId );
					var targetDepth = getItemDepth( $targetLi );
					var $insertPoint = $targetLi;
					var $next = $targetLi.next( 'li.menu-item' );

					while ( $next.length && getItemDepth( $next ) > targetDepth ) {
						$insertPoint = $next;
						$next = $next.next( 'li.menu-item' );
					}

					// تنظیم کلاس‌های عمق آیتم‌های جدید متناسب با والد
					$newItems.each( function () {
						var $item = $( this );
						var curD = getItemDepth( $item );
						setItemDepth( $item, Math.min( 11, targetDepth + 1 + curD ) );
					} );

					$newItems.insertAfter( $insertPoint );
				} else {
					$( '#menu-to-edit' ).append( $newItems );
				}

				syncAllFields();
				refreshItemCheckboxes();
				refreshMenuTreeAndParents();

				var msg = ( i18n.treeSuccess || '%s دسته‌بندی با موفقیت افزوده شدند.' ).replace( '%s', response.data.count );
				notifyUser( msg, true );
			} else {
				var err = ( response && response.data && response.data.message ) ? response.data.message : 'خطا در افزودن دسته‌ها.';
				notifyUser( err, false );
			}
		} ).fail( function () {
			$spinner.removeClass( 'is-active' );
			$btn.prop( 'disabled', false );
			notifyUser( 'خطای ارتباط با سرور.', false );
		} );
	}

	/* -------------------------------------------------------------------------
	 * ۷. مدیریت رویدادهای کاربری (Event Handlers)
	 * ------------------------------------------------------------------------- */

	$( document )
		// تغییر وضعیت مگامنو
		.on( 'change', '[data-ardal-mm-mega]', function () {
			syncMega( $( this ).closest( '[data-ardal-mm-fields]' ) );
		} )
		// تغییر وضعیت تزریق داینامیک
		.on( 'change', '[data-ardal-mm-dynamic]', function () {
			syncDynamic( $( this ).closest( '[data-ardal-mm-fields]' ) );
		} )
		// انتخاب بنر تبلیغاتی با رسانه وردپرس
		.on( 'click', '[data-ardal-mm-pick]', function ( event ) {
			event.preventDefault();
			var $media = $( this ).closest( '[data-ardal-mm-media]' );
			var $input = $media.find( '[data-ardal-mm-image]' );
			var key = $input.attr( 'name' );

			if ( ! frames[ key ] ) {
				frames[ key ] = wp.media( {
					title: $media.hasClass( 'ardal-mm-media--icon' ) ? 'انتخاب آیکون آیتم منو' : 'انتخاب بنر مگا منو',
					button: { text: 'استفاده از این تصویر' },
					library: { type: 'image' },
					multiple: false
				} );

				frames[ key ].on( 'select', function () {
					var image = frames[ key ].state().get( 'selection' ).first().toJSON();
					var src = image.sizes && image.sizes.medium ? image.sizes.medium.url : image.url;

					$input.val( image.id );
					$media.find( '[data-ardal-mm-preview]' ).html( $( '<img>' ).attr( 'src', src ) );
					$input.trigger( 'change' );
				} );
			}

			frames[ key ].open();
		} )
		.on( 'click', '[data-ardal-mm-clear]', function ( event ) {
			event.preventDefault();
			var $media = $( this ).closest( '[data-ardal-mm-media]' );
			$media.find( '[data-ardal-mm-image]' ).val( '' ).trigger( 'change' );
			$media.find( '[data-ardal-mm-preview]' ).empty();
		} )
		// کلیک روی چک‌باکس آیتم (پشتیبانی از Shift + Click)
		.on( 'click', '.ardal-item-cb', function ( event ) {
			event.stopPropagation();

			var $allCbs = $( '#menu-to-edit .ardal-item-cb' );
			var currentIndex = $allCbs.index( this );

			if ( event.shiftKey && lastCheckedIndex !== null ) {
				var start = Math.min( lastCheckedIndex, currentIndex );
				var end = Math.max( lastCheckedIndex, currentIndex );
				var checkedState = $( this ).is( ':checked' );

				$allCbs.slice( start, end + 1 ).prop( 'checked', checkedState );
			}

			lastCheckedIndex = currentIndex;
			updateSelectedState();
		} )
		// جلوگیری از باز شدن آکاردئون هنگام کلیک روی چک‌باکس
		.on( 'click', '.ardal-item-cb-wrap', function ( event ) {
			event.stopPropagation();
		} )
		// انتخاب همه از نوار ابزار
		.on( 'change', '#ardal-select-all-cb', function () {
			var isChecked = $( this ).is( ':checked' );
			$( '#menu-to-edit .ardal-item-cb' ).prop( 'checked', isChecked );
			updateSelectedState();
		} )
		// کلیک روی دکمه انتخاب شاخه (Subtree)
		.on( 'click', '.ardal-branch-pick', function ( event ) {
			event.preventDefault();
			event.stopPropagation();

			var $parentItem = $( this ).closest( 'li.menu-item' );
			var parentDepth = getItemDepth( $parentItem );

			$parentItem.find( '.ardal-item-cb' ).prop( 'checked', true );

			var $next = $parentItem.next( 'li.menu-item' );
			while ( $next.length && getItemDepth( $next ) > parentDepth ) {
				$next.find( '.ardal-item-cb' ).prop( 'checked', true );
				$next = $next.next( 'li.menu-item' );
			}

			updateSelectedState();
		} )
		// دکمه انتقال گروهی
		.on( 'click', '#ardal-btn-move', function ( event ) {
			event.preventDefault();
			bulkMoveItems();
		} )
		// دکمه‌های Indent / Outdent
		.on( 'click', '#ardal-btn-indent', function ( event ) {
			event.preventDefault();
			bulkShiftDepth( 1 );
		} )
		.on( 'click', '#ardal-btn-outdent', function ( event ) {
			event.preventDefault();
			bulkShiftDepth( -1 );
		} )
		// دکمه حذف گروهی
		.on( 'click', '#ardal-btn-bulk-delete', function ( event ) {
			event.preventDefault();
			bulkDeleteItems();
		} );

	/* -------------------------------------------------------------------------
	 * ۸. جابجایی دسته‌جمعی با درگ‌ودراپ آیتم‌های تیک‌خورده (Multi-Drag Sortable)
	 * ------------------------------------------------------------------------- */

	var $draggedCluster = null;
	var clusterOrigDepths = {};
	var clusterLeadOrigDepth = 0;

	$( '#menu-to-edit' )
		.on( 'sortstart', function ( event, ui ) {
			if ( ! ui.item.hasClass( 'is-bulk-selected' ) ) {
				$draggedCluster = null;
				return;
			}

			var $allSelected = $( '#menu-to-edit > li.menu-item.is-bulk-selected' );
			if ( $allSelected.length <= 1 ) {
				$draggedCluster = null;
				return;
			}

			// دیگر آیتم‌های انتخاب‌شده به جز آیتم جاری که کشیده می‌شود
			$draggedCluster = $allSelected.not( ui.item );
			clusterOrigDepths = {};
			clusterLeadOrigDepth = getItemDepth( ui.item );

			$allSelected.each( function () {
				clusterOrigDepths[ getMenuItemId( $( this ) ) ] = getItemDepth( $( this ) );
			} );

			// موقتاً پنهان‌سازی بقیه آیتم‌ها تا درگ به نرمی انجام شود
			$draggedCluster.addClass( 'ardal-cluster-dragging' );

			// افزودن بج بصری زیبا به آیتم در حال درگ
			ui.helper.append(
				'<span class="ardal-drag-badge">📦 جابجایی هم‌زمان ' + $allSelected.length + ' آیتم</span>'
			);
		} )
		.on( 'sortstop', function ( event, ui ) {
			if ( ! $draggedCluster || ! $draggedCluster.length ) {
				return;
			}

			// آشکارسازی مجدد آیتم‌های خوشه
			$draggedCluster.removeClass( 'ardal-cluster-dragging' );
			ui.item.find( '.ardal-drag-badge' ).remove();
			$( '.ardal-drag-badge' ).remove();

			var targetDepth = getItemDepth( ui.item );

			// درج تمام آیتم‌های خوشه بلافاصله بعد از آیتم درگ‌شده
			var $insertPoint = ui.item;

			$draggedCluster.each( function () {
				var $item = $( this );
				var id = getMenuItemId( $item );
				var origD = typeof clusterOrigDepths[ id ] !== 'undefined' ? clusterOrigDepths[ id ] : clusterLeadOrigDepth;
				var relativeOffset = origD - clusterLeadOrigDepth;
				var newDepth = Math.max( 0, Math.min( 11, targetDepth + relativeOffset ) );

				setItemDepth( $item, newDepth );

				$item.insertAfter( $insertPoint );
				$insertPoint = $item;
			} );

			// بازسازی و محاسبه ۱۰۰٪ بی‌نقص تمام والدها و موقعیت‌ها در کل DOM
			refreshMenuTreeAndParents();

			var totalMoved = $draggedCluster.length + 1;
			notifyUser( totalMoved + ' آیتم با موفقیت به‌صورت هم‌زمان جابجا شدند.', true );
			$draggedCluster = null;
		} );

	/* -------------------------------------------------------------------------
	 * ۹. تضمین به‌روزرسانی والدها و موقعیت‌ها هنگام ذخیره فهرست (Save Menu Guard)
	 * ------------------------------------------------------------------------- */

	// بازسازی ساختار درخت بلافاصله قبل از ارسال فرم ذخیره فهرست وردپرس
	$( document )
		.on( 'submit', '#update-nav-menu', function () {
			refreshMenuTreeAndParents();
		} )
		.on( 'click', '#save_menu_header, #save_menu_footer, [name="save_menu"]', function () {
			refreshMenuTreeAndParents();
		} );

	/* -------------------------------------------------------------------------
	 * ۱۰. راه‌اندازی در بارگذاری صفحه و رویدادهای ایجکس وردپرس
	 * ------------------------------------------------------------------------- */

	function initAll() {
		syncAllFields();
		initBulkToolbar();
		initTaxonomyImporter();
		updateVarsCounter();
	}

	$( initAll );

	$( document ).on( 'menu-item-added menu-item-added-multiple', function () {
		window.setTimeout( function () {
			syncAllFields();
			refreshItemCheckboxes();
			refreshMenuTreeAndParents();
		}, 100 );
	} );

	$( document ).ajaxComplete( function ( e, xhr, settings ) {
		if ( settings && settings.data && typeof settings.data === 'string' && settings.data.indexOf( 'action=add-menu-item' ) !== -1 ) {
			window.setTimeout( function () {
				syncAllFields();
				refreshItemCheckboxes();
				refreshMenuTreeAndParents();
			}, 100 );
		}
	} );

}( jQuery ) );
