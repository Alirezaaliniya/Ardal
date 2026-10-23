(function () {
	'use strict';

	function visitorToken() {
		var key = 'ardalCommentVisitor';
		try {
			var token = window.localStorage.getItem(key);
			if (!token || !/^[a-zA-Z0-9_-]{20,80}$/.test(token)) {
				token = '';
				var bytes = new Uint8Array(24);
				if (window.crypto && window.crypto.getRandomValues) {
					window.crypto.getRandomValues(bytes);
					token = Array.prototype.map.call(bytes, function (byte) { return byte.toString(16).padStart(2, '0'); }).join('');
				} else {
					token = Math.random().toString(36).slice(2) + Date.now().toString(36) + Math.random().toString(36).slice(2);
				}
				window.localStorage.setItem(key, token);
			}
			return token;
		} catch (error) {
			return 'guest' + Date.now().toString(36) + Math.random().toString(36).slice(2);
		}
	}

	function send(root, data) {
		data.append('nonce', root.dataset.nonce || '');
		return window.fetch(root.dataset.ajax, { method: 'POST', credentials: 'same-origin', body: data }).then(function (response) {
			return response.json().then(function (json) {
				if (!response.ok || !json.success) {
					throw new Error(json.data && json.data.message ? json.data.message : 'امکان انجام درخواست نیست.');
				}
				return json.data || {};
			});
		});
	}

	function restoreReactions() {
		document.querySelectorAll('[data-ard-comments] [data-comment-id]').forEach(function (card) {
			try {
				var active = window.localStorage.getItem('ardalCommentVote:' + card.dataset.commentId);
				if (active) {
					var button = card.querySelector('[data-reaction="' + active + '"]');
					if (button) button.classList.add('is-active');
				}
			} catch (error) {}
		});
	}

	if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', restoreReactions);
	else restoreReactions();

	document.addEventListener('click', function (event) {
		var more = event.target.closest('[data-rv-more]');
		if (more) {
			var moreRoot = more.closest('[data-ard-comments]');
			var hidden = moreRoot ? moreRoot.querySelectorAll('[data-rv-hidden][hidden]') : [];
			var step = parseInt(more.dataset.step, 10) || 3;
			Array.prototype.slice.call(hidden, 0, step).forEach(function (item) { item.hidden = false; });
			if (hidden.length <= step) more.parentNode.removeChild(more);
			return;
		}

		var reply = event.target.closest('[data-reply-to]');
		if (reply) {
			var root = reply.closest('[data-ard-comments]');
			if (!root) return;
			var form = root.querySelector('[data-comment-form]');
			// اگر فرم در این ریشه نیست، ریشهٔ دیگری با همان post_id که فرم دارد پیدا کن
			// (ویجت فهرست و ویجت فرم ممکن است جدا باشند).
			if (!form) {
				var postId = root.dataset.postId;
				var roots = document.querySelectorAll('[data-ard-comments]');
				for (var i = 0; i < roots.length; i++) {
					if (roots[i].dataset.postId === postId && roots[i].querySelector('[data-comment-form]')) {
						root = roots[i];
						form = root.querySelector('[data-comment-form]');
						break;
					}
				}
			}
			var bar = root.querySelector('[data-replying]');
			var label = bar && bar.querySelector('span');
			if (label) label.textContent = 'در حال پاسخ به ' + (reply.dataset.author || '');
			if (bar) bar.hidden = false;
			root.dataset.parentId = reply.dataset.replyTo;
			if (form) { form.scrollIntoView({ behavior: 'smooth', block: 'center' }); form.querySelector('[name="content"]').focus({ preventScroll: true }); }
			return;
		}

		var cancel = event.target.closest('[data-cancel-reply]');
		if (cancel) {
			var cancelRoot = cancel.closest('[data-ard-comments]');
			if (cancelRoot) { delete cancelRoot.dataset.parentId; var replyBar = cancelRoot.querySelector('[data-replying]'); if (replyBar) replyBar.hidden = true; }
			return;
		}

		var reaction = event.target.closest('[data-reaction]');
		if (reaction) {
			var reactionRoot = reaction.closest('[data-ard-comments]');
			var card = reaction.closest('[data-comment-id]');
			if (!reactionRoot || !card) return;
			var data = new FormData();
			data.append('action', 'ardal_comment_react'); data.append('comment_id', card.dataset.commentId); data.append('type', reaction.dataset.reaction); data.append('visitor', visitorToken());
			reaction.disabled = true;
			send(reactionRoot, data).then(function (result) {
				card.querySelector('[data-vote-count="likes"]').textContent = result.likes;
				card.querySelector('[data-vote-count="dislikes"]').textContent = result.dislikes;
				card.querySelectorAll('[data-reaction]').forEach(function (button) { button.classList.toggle('is-active', button.dataset.reaction === result.active); });
				try {
					var voteKey = 'ardalCommentVote:' + card.dataset.commentId;
					if (result.active) window.localStorage.setItem(voteKey, result.active);
					else window.localStorage.removeItem(voteKey);
				} catch (error) {}
			}).catch(function (error) { window.alert(error.message); }).finally(function () { reaction.disabled = false; });
			return;
		}

		var share = event.target.closest('[data-share-url]');
		if (share) {
			var url = share.dataset.shareUrl;
			var title = share.dataset.shareTitle || document.title;
			if (navigator.share) { navigator.share({ title: title, url: url }).catch(function () {}); }
			else if (navigator.clipboard && navigator.clipboard.writeText) { navigator.clipboard.writeText(url).then(function () { share.classList.add('is-copied'); window.setTimeout(function () { share.classList.remove('is-copied'); }, 1800); }); }
			else { window.prompt('پیوند دیدگاه را کپی کنید:', url); }
		}
	});

	document.addEventListener('submit', function (event) {
		var form = event.target.closest('[data-comment-form]');
		if (!form) return;
		event.preventDefault();
		var root = form.closest('[data-ard-comments]');
		var status = form.querySelector('[data-form-status]');
		var button = form.querySelector('[type="submit"]');
		var data = new FormData(form);
		data.append('action', 'ardal_comment_submit'); data.append('post_id', root.dataset.postId); data.append('parent_id', root.dataset.parentId || '0');
		if (status) status.textContent = 'در حال ارسال…';
		if (button) button.disabled = true;
		send(root, data).then(function (result) {
			if (status) status.textContent = result.message || 'دیدگاه ثبت شد.';
			form.reset();
			delete root.dataset.parentId;
			var bar = root.querySelector('[data-replying]'); if (bar) bar.hidden = true;
			if (result.approved) window.setTimeout(function () { window.location.reload(); }, 700);
		}).catch(function (error) { if (status) status.textContent = error.message; }).finally(function () { if (button) button.disabled = false; });
	});
}());
