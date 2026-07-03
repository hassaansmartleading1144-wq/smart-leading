/**
 * Smart Leading Net — AI Chat (localStorage + restore-on-first-message UI)
 */
(function () {
	'use strict';

	var config = window.slsAiChat || {};
	var HISTORY_KEY = config.historyStorageKey || 'smartleading_chat_history';
	var LEAD_KEY = config.leadStorageKey || 'smartleading_lead_data';
	var LEGACY_KEY = 'smart_leading_chat_history';

	/** Persisted conversation — loaded on init, used for AI context and UI restore. */
	var contextHistory = [];

	var leadData = { name: '', email: '', phone: '' };
	var leadSynced = false;
	var reminderCount = 0;
	var leadFormVisible = false;
	var uiRestored = false;
	var hadStoredHistoryOnLoad = false;

	function extractEmail(text) {
		var match = String(text).match(/\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}\b/);
		return match ? match[0] : '';
	}

	function extractPhone(text) {
		var source = String(text);
		var patterns = [
			/\+?\d{1,3}[-.\s]?\(?\d{3}\)?[-.\s]?\d{3}[-.\s]?\d{4}\b/,
			/\(?\d{3}\)?[-.\s]?\d{3}[-.\s]?\d{4}\b/,
			/\b\d{10,11}\b/
		];
		var i;
		var match;

		for (i = 0; i < patterns.length; i += 1) {
			match = source.match(patterns[i]);
			if (match) {
				return match[0].trim();
			}
		}

		return '';
	}

	function extractName(text, email, phone) {
		var source = String(text);
		var labeled;
		var cleaned;
		var words;

		labeled = source.match(/(?:full\s*name|name)\s*[:\-]\s*([A-Za-z][A-Za-z\s'.-]{1,80})/i);
		if (labeled && labeled[1]) {
			return labeled[1].trim();
		}

		cleaned = source;
		if (email) {
			cleaned = cleaned.replace(email, ' ');
		}
		if (phone) {
			cleaned = cleaned.replace(phone, ' ');
		}

		cleaned = cleaned
			.replace(/(?:full\s*name|name|email(?:\s*address)?|phone(?:\s*number)?)\s*[:\-]\s*/gi, ' ')
			.replace(/[^\w\s'.-]/g, ' ')
			.replace(/\s+/g, ' ')
			.trim();

		words = cleaned.split(' ').filter(Boolean);

		if (words.length >= 2 && words.length <= 5) {
			return words.join(' ');
		}

		return '';
	}

	function collectLeadFields() {
		var userMessages = contextHistory
			.filter(function (entry) {
				return entry.role === 'user';
			})
			.map(function (entry) {
				return entry.content;
			});

		var combinedText = userMessages.join('\n');
		var email = extractEmail(combinedText) || leadData.email;
		var phone = extractPhone(combinedText) || leadData.phone;
		var name = extractName(combinedText, email, phone) || leadData.name;

		return { name: name, email: email, phone: phone };
	}

	function hasCompleteLead(fields) {
		return Boolean(fields.name && fields.email && fields.phone);
	}

	function buildTranscript() {
		return contextHistory
			.map(function (entry) {
				var label = entry.role === 'user' ? 'Visitor' : 'Smart Leading Team';
				return label + ': ' + entry.content;
			})
			.join('\n');
	}

	function trimContextHistory() {
		if (contextHistory.length > 40) {
			contextHistory = contextHistory.slice(-40);
		}
	}

	function persistHistory() {
		try {
			localStorage.setItem(HISTORY_KEY, JSON.stringify(contextHistory));
		} catch (error) {
			// Ignore storage errors.
		}
	}

	function persistLeadData() {
		try {
			localStorage.setItem(
				LEAD_KEY,
				JSON.stringify({
					name: leadData.name,
					email: leadData.email,
					phone: leadData.phone,
					leadSynced: leadSynced,
					reminderCount: reminderCount,
					leadFormVisible: leadFormVisible
				})
			);
		} catch (error) {
			// Ignore storage errors.
		}
	}

	function persistState() {
		persistHistory();
		persistLeadData();
	}

	function migrateLegacyStorage() {
		if (localStorage.getItem(HISTORY_KEY)) {
			return;
		}

		try {
			var raw = localStorage.getItem(LEGACY_KEY);
			if (!raw) {
				return;
			}

			var data = JSON.parse(raw);
			if (data && Array.isArray(data.messages)) {
				localStorage.setItem(HISTORY_KEY, JSON.stringify(data.messages));
				localStorage.setItem(
					LEAD_KEY,
					JSON.stringify({
						name: (data.leadData && data.leadData.name) || '',
						email: (data.leadData && data.leadData.email) || '',
						phone: (data.leadData && data.leadData.phone) || '',
						leadSynced: Boolean(data.leadSynced),
						reminderCount: parseInt(data.reminderCount, 10) || 0,
						leadFormVisible: Boolean(data.leadFormVisible)
					})
				);
			}

			localStorage.removeItem(LEGACY_KEY);
		} catch (error) {
			// Ignore invalid legacy data.
		}
	}

	function loadHiddenContext() {
		migrateLegacyStorage();

		try {
			var historyRaw = localStorage.getItem(HISTORY_KEY);
			if (historyRaw) {
				var messages = JSON.parse(historyRaw);
				if (Array.isArray(messages)) {
					contextHistory = messages;
				}
			}
		} catch (error) {
			contextHistory = [];
		}

		try {
			var leadRaw = localStorage.getItem(LEAD_KEY);
			if (leadRaw) {
				var lead = JSON.parse(leadRaw);
				if (lead && typeof lead === 'object') {
					leadData = {
						name: lead.name || '',
						email: lead.email || '',
						phone: lead.phone || ''
					};
					leadSynced = Boolean(lead.leadSynced);
					reminderCount = parseInt(lead.reminderCount, 10) || 0;
					leadFormVisible = Boolean(lead.leadFormVisible);
				}
			}
		} catch (error) {
			// Keep defaults.
		}

		hadStoredHistoryOnLoad = contextHistory.length > 0;
		uiRestored = false;
	}

	function clearState() {
		try {
			localStorage.removeItem(HISTORY_KEY);
			localStorage.removeItem(LEAD_KEY);
			localStorage.removeItem(LEGACY_KEY);
		} catch (error) {
			// Ignore.
		}
	}

	function initAiChat() {
		var root = document.querySelector('.sls-ai-chat');

		if (!root || !config.ajaxUrl) {
			return;
		}

		var form = root.querySelector('.sls-ai-chat__form');
		var input = root.querySelector('.sls-ai-chat__input');
		var historyEl = root.querySelector('.sls-ai-chat__history');
		var messagesEl = root.querySelector('.sls-ai-chat__messages');
		var typingEl = root.querySelector('.sls-ai-chat__typing');
		var submitBtn = root.querySelector('.sls-ai-chat__submit');
		var leadFormEl;
		var leadNameInput;
		var leadEmailInput;
		var leadPhoneInput;

		if (!form || !input || !messagesEl || !historyEl) {
			return;
		}

		loadHiddenContext();

		function scrollToBottom() {
			messagesEl.scrollTop = messagesEl.scrollHeight;
		}

		function escapeHtml(text) {
			var el = document.createElement('div');
			el.textContent = text;
			return el.innerHTML;
		}

		function formatAiReply(text) {
			return text
				.split(/\n\n+/)
				.map(function (paragraph) {
					return '<p>' + escapeHtml(paragraph).replace(/\n/g, '<br>') + '</p>';
				})
				.join('');
		}

		function createBubble(text, type) {
			var bubble = document.createElement('div');
			bubble.className = 'sls-ai-chat__bubble sls-ai-chat__bubble--' + type;
			bubble.setAttribute('role', 'article');

			if (type === 'ai') {
				bubble.innerHTML = formatAiReply(text);
			} else {
				bubble.textContent = text;
			}

			return bubble;
		}

		function renderBubble(text, type) {
			if (!text) {
				return;
			}

			messagesEl.appendChild(createBubble(text, type));
			historyEl.hidden = false;
			root.classList.add('sls-ai-chat--has-messages');
		}

		function pushContext(role, content) {
			contextHistory.push({ role: role, content: content });
			trimContextHistory();
			persistState();
		}

		function restoreStoredMessagesToUI() {
			var i;

			for (i = 0; i < contextHistory.length; i += 1) {
				renderBubble(
					contextHistory[i].content,
					contextHistory[i].role === 'user' ? 'user' : 'ai'
				);
			}

			if (leadFormVisible && !leadSynced) {
				showLeadForm();
			}

			uiRestored = true;
			scrollToBottom();
		}

		function ensureUiRestored() {
			if (uiRestored) {
				return;
			}

			if (hadStoredHistoryOnLoad && contextHistory.length > 0) {
				restoreStoredMessagesToUI();
				return;
			}

			uiRestored = true;
		}

		function addAssistantMessage(text) {
			renderBubble(text, 'ai');
			pushContext('assistant', text);
			scrollToBottom();
		}

		function addUserMessage(text) {
			renderBubble(text, 'user');
			pushContext('user', text);
			scrollToBottom();
		}

		function setLoading(isLoading) {
			if (typingEl) {
				typingEl.hidden = !isLoading;
			}

			if (submitBtn) {
				submitBtn.disabled = isLoading;
			}

			input.disabled = isLoading;

			if (isLoading) {
				scrollToBottom();
			}
		}

		function buildLeadForm() {
			var wrap = document.createElement('div');
			wrap.className = 'sls-ai-chat__lead-form';
			wrap.hidden = true;

			wrap.innerHTML =
				'<p class="sls-ai-chat__lead-form-intro">' + escapeHtml(config.leadFormIntro || '') + '</p>' +
				'<label class="sls-ai-chat__lead-label">' + escapeHtml(config.leadNameLabel || 'Full Name') +
				'<input type="text" class="sls-ai-chat__lead-input" name="lead_name" autocomplete="name" required></label>' +
				'<label class="sls-ai-chat__lead-label">' + escapeHtml(config.leadEmailLabel || 'Email') +
				'<input type="email" class="sls-ai-chat__lead-input" name="lead_email" autocomplete="email" required></label>' +
				'<label class="sls-ai-chat__lead-label">' + escapeHtml(config.leadPhoneLabel || 'Phone') +
				'<input type="tel" class="sls-ai-chat__lead-input" name="lead_phone" autocomplete="tel" required></label>' +
				'<button type="button" class="sls-ai-chat__lead-submit">' +
				escapeHtml(config.continueChatLabel || 'Continue Chat') + '</button>';

			historyEl.appendChild(wrap);

			leadFormEl = wrap;
			leadNameInput = wrap.querySelector('[name="lead_name"]');
			leadEmailInput = wrap.querySelector('[name="lead_email"]');
			leadPhoneInput = wrap.querySelector('[name="lead_phone"]');

			wrap.querySelector('.sls-ai-chat__lead-submit').addEventListener('click', handleLeadFormSubmit);
		}

		function buildClearButton() {
			var clearBtn = document.createElement('button');
			clearBtn.type = 'button';
			clearBtn.className = 'sls-ai-chat__clear';
			clearBtn.textContent = config.clearChatLabel || 'Clear Chat';
			clearBtn.addEventListener('click', clearChat);
			historyEl.insertBefore(clearBtn, messagesEl);
		}

		function showLeadForm() {
			if (!leadFormEl) {
				return;
			}

			leadFormVisible = true;
			leadFormEl.hidden = false;

			if (leadData.name) {
				leadNameInput.value = leadData.name;
			}
			if (leadData.email) {
				leadEmailInput.value = leadData.email;
			}
			if (leadData.phone) {
				leadPhoneInput.value = leadData.phone;
			}

			persistLeadData();
			scrollToBottom();
		}

		function hideLeadForm() {
			if (!leadFormEl) {
				return;
			}

			leadFormVisible = false;
			leadFormEl.hidden = true;
			persistLeadData();
		}

		function saveLeadToGhl(fields) {
			var body = new FormData();
			body.append('action', config.leadAction || 'sls_ai_chat_save_lead');
			body.append('nonce', config.nonce || '');
			body.append('name', fields.name);
			body.append('email', fields.email);
			body.append('phone', fields.phone);
			body.append('conversation', buildTranscript());
			body.append('transcript', buildTranscript());

			return fetch(config.ajaxUrl, {
				method: 'POST',
				credentials: 'same-origin',
				body: body
			}).then(function (response) {
				return response.json();
			});
		}

		function completeLeadSync(fields, successMessage) {
			leadSynced = true;
			leadData = {
				name: fields.name,
				email: fields.email,
				phone: fields.phone
			};
			hideLeadForm();
			addAssistantMessage(successMessage || config.leadSuccessMessage);
		}

		function handleLeadFormSubmit() {
			ensureUiRestored();

			var fields = {
				name: leadNameInput.value.trim(),
				email: leadEmailInput.value.trim(),
				phone: leadPhoneInput.value.trim()
			};

			if (!hasCompleteLead(fields)) {
				leadNameInput.focus();
				return;
			}

			if (!extractEmail(fields.email)) {
				leadEmailInput.focus();
				return;
			}

			setLoading(true);

			saveLeadToGhl(fields)
				.then(function (data) {
					if (data && data.success) {
						completeLeadSync(fields, data.message || config.leadSuccessMessage);
						return;
					}

					var errorMessage = (data && data.message) || config.errorMessage || 'Lead could not be saved.';
					addAssistantMessage(errorMessage);
				})
				.catch(function () {
					addAssistantMessage(config.errorMessage || 'Lead could not be saved. Please try again.');
				})
				.finally(function () {
					setLoading(false);
					input.focus();
				});
		}

		function handleLeadCapture(fields) {
			var userMessage = contextHistory.length
				? contextHistory[contextHistory.length - 1].content
				: '';

			setLoading(true);

			saveLeadToGhl(fields)
				.then(function (data) {
					if (data && data.success) {
						completeLeadSync(fields, data.message || config.leadSuccessMessage);
						setLoading(false);
						input.focus();
						return;
					}

					var errorMessage = (data && data.message) || config.errorMessage || 'Lead could not be saved.';
					addAssistantMessage(errorMessage);
					setLoading(false);
					input.focus();
				})
				.catch(function () {
					addAssistantMessage(config.errorMessage || 'Lead could not be saved. Please try again.');
					setLoading(false);
					input.focus();
				});
		}

		function sendMessage(message) {
			setLoading(true);

			var historyForApi = contextHistory.slice(0, -1);
			var body = new FormData();
			body.append('action', config.action || 'sls_ai_chat');
			body.append('nonce', config.nonce || '');
			body.append('message', message);
			body.append('history', JSON.stringify(historyForApi));

			fetch(config.ajaxUrl, {
				method: 'POST',
				credentials: 'same-origin',
				body: body
			})
				.then(function (response) {
					return response.json();
				})
				.then(function (data) {
					if (!data || !data.success || !data.reply) {
						throw new Error((data && data.message) || config.errorMessage);
					}

					addAssistantMessage(data.reply);
				})
				.catch(function () {
					addAssistantMessage(config.errorMessage || 'Sorry, something went wrong. Please try again.');
				})
				.finally(function () {
					setLoading(false);
					input.focus();
				});
		}

		function handleLeadReminderFlow() {
			if (reminderCount === 1) {
				addAssistantMessage(config.welcomeMessage || '');
				return true;
			}

			if (reminderCount === 2) {
				addAssistantMessage(config.reminderOne || '');
				return true;
			}

			if (reminderCount === 3) {
				addAssistantMessage(config.reminderTwo || '');
				return true;
			}

			if (reminderCount >= 4) {
				showLeadForm();
				return true;
			}

			return false;
		}

		function handleSubmit(event) {
			if (event) {
				event.preventDefault();
			}

			var message = input.value.trim();

			if (!message) {
				input.focus();
				return;
			}

			input.value = '';
			ensureUiRestored();
			addUserMessage(message);

			var fields = collectLeadFields();
			leadData = fields;

			if (!leadSynced && hasCompleteLead(fields)) {
				handleLeadCapture(fields);
				return;
			}

			if (!leadSynced) {
				reminderCount += 1;
				persistLeadData();

				if (handleLeadReminderFlow()) {
					return;
				}
			}

			sendMessage(message);
		}

		function clearChat() {
			contextHistory = [];
			leadData = { name: '', email: '', phone: '' };
			leadSynced = false;
			reminderCount = 0;
			leadFormVisible = false;
			uiRestored = false;
			hadStoredHistoryOnLoad = false;
			clearState();

			messagesEl.innerHTML = '';

			if (leadFormEl) {
				leadNameInput.value = '';
				leadEmailInput.value = '';
				leadPhoneInput.value = '';
				leadFormEl.hidden = true;
			}

			historyEl.hidden = true;
			root.classList.remove('sls-ai-chat--has-messages');
			input.focus();
		}

		buildClearButton();
		buildLeadForm();

		form.addEventListener('submit', handleSubmit);

		input.addEventListener('keydown', function (event) {
			if (event.key === 'Enter' && !event.shiftKey) {
				event.preventDefault();
				handleSubmit(event);
			}
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initAiChat);
	} else {
		initAiChat();
	}
})();
