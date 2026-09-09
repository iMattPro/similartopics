// Dynamic Similar Topics - Real-time search as user types in the subject field
(function() {
	'use strict';

	// State variables: debounce timer, request sequence, keyboard selection index, cached DOM elements
	let searchTimeout, activeRequest, searchSequence = 0, selectedIndex = -1, cachedItems = [];
	const dropdown = document.getElementById('similar-topics-dropdown');
	const subjectField = document.getElementById('subject');
	const listContainer = document.getElementById('similar-topics-list');

	if (!subjectField || !dropdown) {
		return;
	}

	// Disable browser autocomplete to prevent interference with our dropdown
	subjectField.setAttribute('autocomplete', 'off');

	// Hide dropdown and reset selection state
	function hideDropdown() {
		searchSequence++;
		if (activeRequest) {
			activeRequest.abort();
			activeRequest = null;
		}
		dropdown.style.display = 'none';
		selectedIndex = -1;
		cachedItems = [];
	}

	// Position dropdown below the subject field and show it
	function showDropdown() {
		const rect = subjectField.getBoundingClientRect();
		const isRTL = document.documentElement.dir === 'rtl' || document.body.classList.contains('rtl');

		Object.assign(dropdown.style, {
			left: isRTL ? 'auto' : (rect.left + window.scrollX) + 'px',
			right: 'auto',
			top: 'auto',
			width: rect.width + 'px',
			display: 'block'
		});

		const offsetParent = dropdown.offsetParent;
		if (offsetParent) {
			const parentRect = offsetParent.getBoundingClientRect();
			const isRoot = offsetParent === document.body || offsetParent === document.documentElement;
			const scrollTop = isRoot ? 0 : offsetParent.scrollTop;

			// Absolute offsets use the offset parent's padding box, not the viewport.
			dropdown.style.top = (rect.bottom - parentRect.top - offsetParent.clientTop + scrollTop) + 'px';
		}

		// Preserve browser's natural RTL placement, then make its offset explicit.
		if (isRTL && offsetParent) {
			dropdown.style.right = (offsetParent.clientWidth - dropdown.offsetLeft - dropdown.offsetWidth) + 'px';
		}
	}

	// AJAX search for similar topics (minimum 3 characters)
	function searchSimilarTopics(query, sequence) {
		if (query.length < 3) {
			hideDropdown();
			return;
		}

		const xhr = activeRequest = new XMLHttpRequest();
		const baseUrl = dropdown.dataset.searchUrl;
		const separator = baseUrl.includes('?') ? '&' : '?';
		xhr.open('GET', baseUrl + separator + 'q=' + encodeURIComponent(query) + '&f=' + encodeURIComponent(dropdown.dataset.forumId));
		xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

		xhr.onreadystatechange = () => {
			if (xhr.readyState !== 4) {
				return;
			}

			if (activeRequest === xhr) {
				activeRequest = null;
			}
			if (sequence !== searchSequence || query !== subjectField.value.trim()) {
				return;
			}

			if (xhr.status !== 200) {
				hideDropdown();
				return;
			}

			try {
				const response = JSON.parse(xhr.responseText);
				displayResults(response.topics);
			} catch (e) {
				hideDropdown();
			}
		};

		xhr.send();
	}

	// Build and display topic results in a dropdown
	function displayResults(topics) {
		if (!topics || topics.length === 0) {
			return hideDropdown();
		}

		listContainer.innerHTML = '';
		topics.forEach(topic => {
			const item = document.createElement('div');
			item.className = 'similar-topic-item';
			const link = document.createElement('a');
			link.href = topic.url;
			link.className = 'similar-topic-title';
			link.textContent = topic.title;
			link.target = '_blank';
			link.rel = 'noopener noreferrer';
			item.appendChild(link);
			listContainer.appendChild(item);
		});

		// Cache items for keyboard navigation performance
		cachedItems = listContainer.children;
		selectedIndex = -1;
		showDropdown();
	}

	// Update visual selection highlighting for keyboard navigation
	function updateSelection() {
		for (let i = 0; i < cachedItems.length; i++) {
			cachedItems[i].classList.toggle('selected', i === selectedIndex);
		}
	}

	// Open currently selected topic in new tab
	function openSelectedTopic() {
		if (selectedIndex >= 0 && selectedIndex < cachedItems.length) {
			window.open(cachedItems[selectedIndex].querySelector('a').href, '_blank', 'noopener,noreferrer');
			hideDropdown();
		}
	}

	// Debounced search on input (300 ms delay)
	subjectField.addEventListener('input', () => {
		clearTimeout(searchTimeout);
		hideDropdown();
		const query = subjectField.value.trim();
		const sequence = searchSequence;

		if (query.length < 3) {
			return;
		}

		searchTimeout = setTimeout(() => {
			searchSimilarTopics(query, sequence);
		}, 300);
	});

	// Keyboard navigation: Arrow keys to select, Enter to open
	subjectField.addEventListener('keydown', (e) => {
		if (dropdown.style.display === 'none' || !cachedItems.length) {
			return;
		}

		const { key, shiftKey, ctrlKey, altKey, metaKey } = e;
		if ((key === 'ArrowDown' || key === 'ArrowUp') && !shiftKey && !ctrlKey && !altKey && !metaKey) {
			e.preventDefault();
			// Cycle through items (wraps around at ends)
			selectedIndex = key === 'ArrowDown'
				? (selectedIndex < cachedItems.length - 1 ? selectedIndex + 1 : 0)
				: (selectedIndex > 0 ? selectedIndex - 1 : cachedItems.length - 1);
			updateSelection();
		} else if (key === 'Enter' && selectedIndex >= 0) {
			e.preventDefault();
			openSelectedTopic();
		}
	});

	// Hide dropdown when the field loses focus (delayed to allow clicks)
	subjectField.addEventListener('blur', () => {
		setTimeout(hideDropdown, 200);
	});

	// Hide dropdown when clicking outside
	document.addEventListener('click', (e) => {
		if (!dropdown.contains(e.target) && e.target !== subjectField) {
			hideDropdown();
		}
	});
})();
