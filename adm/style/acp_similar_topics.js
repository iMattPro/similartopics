(function () {
	'use strict';

	var root = document.getElementById('pst-acp');
	if (!root) {
		return;
	}

	var form = document.getElementById('acp_similar_topics');
	var labels = document.getElementById('pst-labels');
	var forumFilter = document.getElementById('pst-forum-filter');
	var forumCards = root.querySelectorAll('.pst-forum-card');
	var noResults = root.querySelector('.pst-no-results');
	var sensitivity = document.getElementById('pst_sense');
	var sensitivityOutput = document.getElementById('pst-sense-value');
	var cacheInput = document.getElementById('pst_cache');
	var cacheSlider = document.getElementById('pst-cache-slider');
	var cacheOutput = document.getElementById('pst-cache-value');
	var cacheOptions = document.querySelectorAll('#pst-cache-options option');
	var modal = document.getElementById('pst-source-modal');
	var activeCard = null;
	var lastTrigger = null;
	var initialSettingsState = '';

	function each(items, callback) {
		Array.prototype.forEach.call(items, callback);
	}

	function settingsState() {
		var values = [];

		each(form.elements, function (field) {
			var type = (field.type || '').toLowerCase();
			if (!field.name || field.disabled || field.name === 'pst_source_mode_dialog' || type === 'submit' || type === 'reset' || type === 'button') {
				return;
			}

			if ((type === 'checkbox' || type === 'radio') && !field.checked) {
				return;
			}

			if (field.multiple) {
				each(field.options, function (option) {
					if (option.selected) {
						values.push(encodeURIComponent(field.name) + '=' + encodeURIComponent(option.value));
					}
				});
				return;
			}

			values.push(encodeURIComponent(field.name) + '=' + encodeURIComponent(field.value));
		});

		return values.sort().join('&');
	}

	function updateDirtyState() {
		var dirty = settingsState() !== initialSettingsState;
		form.classList.toggle('is-dirty', dirty);
	}

	function updateSensitivity() {
		if (sensitivity && sensitivityOutput) {
			sensitivityOutput.value = sensitivity.value;
			sensitivityOutput.textContent = sensitivity.value;
		}
	}

	if (sensitivity) {
		sensitivity.addEventListener('input', updateSensitivity);
	}

	function updateCacheDuration() {
		if (!cacheInput || !cacheSlider || !cacheOutput) {
			return;
		}

		var option = cacheOptions[parseInt(cacheSlider.value, 10)];
		if (option) {
			cacheInput.value = option.getAttribute('data-seconds');
			cacheOutput.value = option.getAttribute('data-label');
			cacheOutput.textContent = option.getAttribute('data-label');
		}
	}

	if (cacheSlider) {
		cacheSlider.addEventListener('input', updateCacheDuration);
	}

	initialSettingsState = settingsState();
	each(forumCards, function (card) {
		var sourceMode = card.querySelector('.pst-source-mode');
		var sourceValues = card.querySelector('.pst-source-values');
		sourceMode.setAttribute('data-initial-value', sourceMode.value);
		sourceValues.setAttribute('data-initial-value', sourceValues.value);
	});
	form.classList.add('pst-dirty-tracking');
	form.addEventListener('input', updateDirtyState);
	form.addEventListener('change', updateDirtyState);
	updateDirtyState();

	if (forumFilter) {
		forumFilter.addEventListener('input', function () {
			var query = forumFilter.value.toLocaleLowerCase();
			var visible = 0;
			each(forumCards, function (card) {
				var matches = card.getAttribute('data-forum-name').toLocaleLowerCase().indexOf(query) !== -1;
				card.hidden = !matches;
				visible += matches ? 1 : 0;
			});
			if (noResults) {
				noResults.hidden = visible !== 0;
			}
		});
	}

	form.addEventListener('reset', function () {
		window.setTimeout(function () {
			if (forumFilter) {
				forumFilter.value = '';
			}
			if (noResults) {
				noResults.hidden = true;
			}
			each(forumCards, function (card) {
				var sourceMode = card.querySelector('.pst-source-mode');
				var sourceValues = card.querySelector('.pst-source-values');
				card.hidden = false;
				sourceMode.value = sourceMode.getAttribute('data-initial-value');
				sourceValues.value = sourceValues.getAttribute('data-initial-value');
				updateCardSummary(card);
			});
			if (sourceFilter) {
				sourceFilter.value = '';
			}
			if (sourceOptions) {
				each(sourceOptions, function (option) {
					option.hidden = false;
				});
			}
			updateSensitivity();
			if (cacheOutput) {
				cacheOutput.value = cacheOutput.getAttribute('data-default-label');
				cacheOutput.textContent = cacheOutput.getAttribute('data-default-label');
			}
			if (modal && modal.classList.contains('is-open')) {
				closeModal();
			}
			updateDirtyState();
		}, 0);
	});

	if (!modal) {
		return;
	}

	var modalForum = document.getElementById('pst-modal-forum');
	var modeInputs = modal.querySelectorAll('input[name="pst_source_mode_dialog"]');
	var sourcePicker = document.getElementById('pst-source-picker');
	var sourceFilter = document.getElementById('pst-source-filter');
	var sourceOptions = modal.querySelectorAll('.pst-source-options label');
	var applyButton = document.getElementById('pst-apply-sources');
	var selectAvailable = document.getElementById('pst-select-available');
	var clearSources = document.getElementById('pst-clear-sources');
	var modalError = document.getElementById('pst-modal-error');

	function selectedMode() {
		var mode = 'all';
		each(modeInputs, function (input) {
			if (input.checked) {
				mode = input.value;
			}
		});
		return mode;
	}

	function setMode(mode) {
		each(modeInputs, function (input) {
			input.checked = input.value === mode;
		});
		sourcePicker.classList.toggle('is-disabled', mode !== 'custom');
		modalError.hidden = true;
	}

	function selectedSourceIds() {
		var selected = [];
		each(sourceOptions, function (option) {
			var checkbox = option.querySelector('input[type="checkbox"]');
			if (checkbox.checked) {
				selected.push(checkbox.value);
			}
		});
		return selected;
	}

	function updateAvailability() {
		each(sourceOptions, function (option) {
			var id = option.getAttribute('data-source-id');
			var toggle = document.getElementById('searchable-forum-' + id);
			var badge = option.querySelector('.pst-availability');
			var available = toggle ? toggle.checked : false;
			badge.setAttribute('data-available', available ? '1' : '0');
			badge.classList.toggle('is-unavailable', !available);
			badge.textContent = available ? labels.getAttribute('data-available') : labels.getAttribute('data-unavailable');
		});
	}

	function updateCardSummary(card) {
		var mode = card.querySelector('.pst-source-mode').value;
		var values = card.querySelector('.pst-source-values').value;
		var count = values ? values.split(',').filter(function (value) { return value !== ''; }).length : 0;
		var summary = card.querySelector('.pst-source-summary');

		if (mode !== 'custom' || count === 0) {
			summary.textContent = labels.getAttribute('data-all');
			summary.classList.remove('is-custom');
			return;
		}

		summary.textContent = count === 1 ? labels.getAttribute('data-custom-one') : labels.getAttribute('data-custom-many').replace('%d', count);
		summary.classList.add('is-custom');
	}

	function openModal(card, trigger) {
		activeCard = card;
		lastTrigger = trigger;
		modalForum.textContent = card.getAttribute('data-forum-name');
		var mode = card.querySelector('.pst-source-mode').value;
		var selected = card.querySelector('.pst-source-values').value.split(',');

		each(sourceOptions, function (option) {
			var checkbox = option.querySelector('input[type="checkbox"]');
			checkbox.checked = selected.indexOf(checkbox.value) !== -1;
			option.hidden = false;
		});
		sourceFilter.value = '';
		updateAvailability();
		setMode(mode);
		modal.classList.add('is-open');
		modal.setAttribute('aria-hidden', 'false');
		document.body.classList.add('pst-modal-open');
		modal.querySelector('input[name="pst_source_mode_dialog"]:checked').focus();
	}

	function closeModal() {
		modal.classList.remove('is-open');
		modal.setAttribute('aria-hidden', 'true');
		document.body.classList.remove('pst-modal-open');
		activeCard = null;
		if (lastTrigger) {
			lastTrigger.focus();
		}
	}

	each(root.querySelectorAll('.pst-source-button'), function (button) {
		button.addEventListener('click', function () {
			openModal(button.closest('.pst-forum-card'), button);
		});
	});

	each(root.querySelectorAll('[data-pst-close]'), function (button) {
		button.addEventListener('click', closeModal);
	});

	each(modeInputs, function (input) {
		input.addEventListener('change', function () {
			setMode(input.value);
		});
	});

	sourceFilter.addEventListener('input', function () {
		var query = sourceFilter.value.toLocaleLowerCase();
		each(sourceOptions, function (option) {
			option.hidden = option.getAttribute('data-source-name').toLocaleLowerCase().indexOf(query) === -1;
		});
	});

	selectAvailable.addEventListener('click', function () {
		each(sourceOptions, function (option) {
			option.querySelector('input[type="checkbox"]').checked = option.querySelector('.pst-availability').getAttribute('data-available') === '1';
		});
	});

	clearSources.addEventListener('click', function () {
		each(sourceOptions, function (option) {
			option.querySelector('input[type="checkbox"]').checked = false;
		});
	});

	applyButton.addEventListener('click', function () {
		var mode = selectedMode();
		var selected = selectedSourceIds();

		if (mode === 'custom' && selected.length === 0) {
			modalError.hidden = false;
			return;
		}

		activeCard.querySelector('.pst-source-mode').value = mode;
		activeCard.querySelector('.pst-source-values').value = mode === 'custom' ? selected.join(',') : '';
		updateCardSummary(activeCard);
		updateDirtyState();
		closeModal();
	});

	document.addEventListener('keydown', function (event) {
		if (event.key === 'Escape' && modal.classList.contains('is-open')) {
			closeModal();
		}
	});

}());
