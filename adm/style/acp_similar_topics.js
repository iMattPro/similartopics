(() => {
	'use strict';

	const root = document.getElementById('pst-acp');
	if (!root) {
		return;
	}

	const form = document.getElementById('acp_similar_topics');
	const labels = document.getElementById('pst-labels');
	const sourceCountLabels = new Map(Array.from(labels.querySelectorAll('[data-source-count]'))
		.map((label) => [Number.parseInt(label.dataset.sourceCount, 10), label.textContent]));
	const forumFilter = document.getElementById('pst-forum-filter');
	const forumCards = root.querySelectorAll('.pst-forum-card');
	const noResults = root.querySelector('.pst-no-results');
	const sensitivity = document.getElementById('pst_sense');
	const sensitivityOutput = document.getElementById('pst-sense-value');
	const cacheInput = document.getElementById('pst_cache');
	const cacheSlider = document.getElementById('pst-cache-slider');
	const cacheOutput = document.getElementById('pst-cache-value');
	const cacheOptions = document.querySelectorAll('#pst-cache-options option');
	const modal = document.getElementById('pst-source-modal');
	const modalForum = modal ? document.getElementById('pst-modal-forum') : null;
	const modeInputs = modal ? modal.querySelectorAll('input[name="pst_source_mode_dialog"]') : [];
	const sourcePicker = modal ? document.getElementById('pst-source-picker') : null;
	const sourceFilter = modal ? document.getElementById('pst-source-filter') : null;
	const sourceOptions = modal ? modal.querySelectorAll('.pst-source-options label') : [];
	const applyButton = modal ? document.getElementById('pst-apply-sources') : null;
	const selectAvailable = modal ? document.getElementById('pst-select-available') : null;
	const clearSources = modal ? document.getElementById('pst-clear-sources') : null;
	const modalError = modal ? document.getElementById('pst-modal-error') : null;
	let activeCard = null;
	let lastTrigger = null;
	let initialSettingsState = '';

	const settingsState = () => {
		const values = [];

		Array.from(form.elements).forEach((field) => {
			const type = (field.type || '').toLowerCase();
			const ignoredType = ['submit', 'reset', 'button'].includes(type);
			if (!field.name || field.disabled || field.name === 'pst_source_mode_dialog' || ignoredType) {
				return;
			}

			if (['checkbox', 'radio'].includes(type) && !field.checked) {
				return;
			}

			if (field.multiple) {
				Array.from(field.options)
					.filter((option) => option.selected)
					.forEach((option) => values.push(`${encodeURIComponent(field.name)}=${encodeURIComponent(option.value)}`));
				return;
			}

			values.push(`${encodeURIComponent(field.name)}=${encodeURIComponent(field.value)}`);
		});

		return values.sort().join('&');
	};

	const updateDirtyState = () => {
		form.classList.toggle('is-dirty', settingsState() !== initialSettingsState);
	};

	const updateSensitivity = () => {
		if (sensitivity && sensitivityOutput) {
			sensitivityOutput.value = sensitivity.value;
			sensitivityOutput.textContent = sensitivity.value;
		}
	};

	const updateCacheDuration = () => {
		if (!cacheInput || !cacheSlider || !cacheOutput) {
			return;
		}

		const option = cacheOptions[Number.parseInt(cacheSlider.value, 10)];
		if (option) {
			cacheInput.value = option.dataset.seconds;
			cacheOutput.value = option.dataset.label;
			cacheOutput.textContent = option.dataset.label;
		}
	};

	const selectedMode = () => {
		const selected = Array.from(modeInputs).find((input) => input.checked);
		return selected ? selected.value : 'all';
	};

	const setMode = (mode) => {
		modeInputs.forEach((input) => {
			input.checked = input.value === mode;
		});
		sourcePicker.classList.toggle('is-disabled', mode !== 'custom');
		modalError.hidden = true;
	};

	const selectedSourceIds = () => Array.from(sourceOptions)
		.map((option) => option.querySelector('input[type="checkbox"]'))
		.filter((checkbox) => checkbox.checked)
		.map((checkbox) => checkbox.value);

	const updateAvailability = () => {
		sourceOptions.forEach((option) => {
			const toggle = document.getElementById(`searchable-forum-${option.dataset.sourceId}`);
			const badge = option.querySelector('.pst-availability');
			const available = toggle ? toggle.checked : false;
			badge.dataset.available = available ? '1' : '0';
			badge.classList.toggle('is-unavailable', !available);
			badge.textContent = available ? labels.dataset.available : labels.dataset.unavailable;
		});
	};

	const updateCardSummary = (card) => {
		const mode = card.querySelector('.pst-source-mode').value;
		const values = card.querySelector('.pst-source-values').value;
		const count = values ? values.split(',').filter(Boolean).length : 0;
		const summary = card.querySelector('.pst-source-summary');

		if (mode !== 'custom' || count === 0) {
			summary.textContent = labels.dataset.all;
			summary.classList.remove('is-custom');
			return;
		}

		summary.textContent = sourceCountLabels.get(count) || count.toString();
		summary.classList.add('is-custom');
	};

	const modalFocusableElements = () => Array.from(modal.querySelectorAll(
		'a[href], button:not([disabled]), input:not([disabled]):not([type="hidden"]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex^="-"])',
	)).filter((element) => element.getClientRects().length > 0);

	const closeModal = () => {
		modal.classList.remove('is-open');
		modal.setAttribute('aria-hidden', 'true');
		document.body.classList.remove('pst-modal-open');
		activeCard = null;
		if (lastTrigger) {
			lastTrigger.focus();
		}
	};

	const openModal = (card, trigger) => {
		activeCard = card;
		lastTrigger = trigger;
		modalForum.textContent = card.dataset.forumName;
		const mode = card.querySelector('.pst-source-mode').value;
		const selected = card.querySelector('.pst-source-values').value.split(',');

		sourceOptions.forEach((option) => {
			const checkbox = option.querySelector('input[type="checkbox"]');
			checkbox.checked = selected.includes(checkbox.value);
			option.hidden = false;
		});
		sourceFilter.value = '';
		updateAvailability();
		setMode(mode);
		modal.classList.add('is-open');
		modal.setAttribute('aria-hidden', 'false');
		document.body.classList.add('pst-modal-open');
		modal.querySelector('input[name="pst_source_mode_dialog"]:checked').focus();
	};

	const resetForm = () => {
		window.setTimeout(() => {
			if (forumFilter) {
				forumFilter.value = '';
			}
			if (noResults) {
				noResults.hidden = true;
			}

			forumCards.forEach((card) => {
				const sourceMode = card.querySelector('.pst-source-mode');
				const sourceValues = card.querySelector('.pst-source-values');
				card.hidden = false;
				sourceMode.value = sourceMode.dataset.initialValue;
				sourceValues.value = sourceValues.dataset.initialValue;
				updateCardSummary(card);
			});

			if (sourceFilter) {
				sourceFilter.value = '';
			}
			sourceOptions.forEach((option) => {
				option.hidden = false;
			});

			updateSensitivity();
			if (cacheOutput) {
				cacheOutput.value = cacheOutput.dataset.defaultLabel;
				cacheOutput.textContent = cacheOutput.dataset.defaultLabel;
			}
			if (modal && modal.classList.contains('is-open')) {
				closeModal();
			}
			updateDirtyState();
		}, 0);
	};

	if (sensitivity) {
		sensitivity.addEventListener('input', updateSensitivity);
	}
	if (cacheSlider) {
		cacheSlider.addEventListener('input', updateCacheDuration);
	}

	initialSettingsState = settingsState();
	forumCards.forEach((card) => {
		const sourceMode = card.querySelector('.pst-source-mode');
		const sourceValues = card.querySelector('.pst-source-values');
		sourceMode.dataset.initialValue = sourceMode.value;
		sourceValues.dataset.initialValue = sourceValues.value;
	});
	form.classList.add('pst-dirty-tracking');
	form.addEventListener('input', updateDirtyState);
	form.addEventListener('change', updateDirtyState);
	form.addEventListener('reset', resetForm);
	updateDirtyState();

	if (forumFilter) {
		forumFilter.addEventListener('input', () => {
			const query = forumFilter.value.toLocaleLowerCase();
			let visible = 0;
			forumCards.forEach((card) => {
				const matches = card.dataset.forumName.toLocaleLowerCase().includes(query);
				card.hidden = !matches;
				visible += matches ? 1 : 0;
			});
			if (noResults) {
				noResults.hidden = visible !== 0;
			}
		});
	}

	if (!modal) {
		return;
	}

	root.querySelectorAll('.pst-source-button').forEach((button) => {
		button.addEventListener('click', () => openModal(button.closest('.pst-forum-card'), button));
	});

	root.querySelectorAll('[data-pst-close]').forEach((button) => {
		button.addEventListener('click', closeModal);
	});

	modeInputs.forEach((input) => {
		input.addEventListener('change', () => setMode(input.value));
	});

	sourceFilter.addEventListener('input', () => {
		const query = sourceFilter.value.toLocaleLowerCase();
		sourceOptions.forEach((option) => {
			option.hidden = !option.dataset.sourceName.toLocaleLowerCase().includes(query);
		});
	});

	selectAvailable.addEventListener('click', () => {
		sourceOptions.forEach((option) => {
			option.querySelector('input[type="checkbox"]').checked = option.querySelector('.pst-availability').dataset.available === '1';
		});
	});

	clearSources.addEventListener('click', () => {
		sourceOptions.forEach((option) => {
			option.querySelector('input[type="checkbox"]').checked = false;
		});
	});

	applyButton.addEventListener('click', () => {
		const mode = selectedMode();
		const selected = selectedSourceIds();

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

	document.addEventListener('keydown', (event) => {
		if (!modal.classList.contains('is-open')) {
			return;
		}

		if (event.key === 'Escape') {
			closeModal();
			return;
		}

		if (event.key !== 'Tab') {
			return;
		}

		const focusableElements = modalFocusableElements();
		if (focusableElements.length === 0) {
			event.preventDefault();
			return;
		}

		const firstElement = focusableElements[0];
		const lastElement = focusableElements[focusableElements.length - 1];
		const focusOutsideModal = !modal.contains(document.activeElement);

		if (event.shiftKey && (document.activeElement === firstElement || focusOutsideModal)) {
			event.preventDefault();
			lastElement.focus();
		} else if (!event.shiftKey && (document.activeElement === lastElement || focusOutsideModal)) {
			event.preventDefault();
			firstElement.focus();
		}
	}, true);

	document.addEventListener('focusin', (event) => {
		if (!modal.classList.contains('is-open') || modal.contains(event.target)) {
			return;
		}

		const firstElement = modalFocusableElements()[0];
		if (firstElement) {
			firstElement.focus();
		}
	});
})();
