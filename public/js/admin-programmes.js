(function () {
  const scriptEl = document.getElementById('admin-programmes-js');
  const publishBaseUrl = scriptEl ? scriptEl.dataset.publishUrlBase || '' : '';
  const csrfToken = scriptEl ? scriptEl.dataset.csrfToken || '' : '';

  function showFlash(message, type) {
    const kind = type || 'success';
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-' + kind + ' alert-dismissible fade show';
    alertDiv.role = 'alert';
    alertDiv.innerHTML =
      message +
      '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';

    const container = document.querySelector('.table-responsive');
    if (!container || !container.parentNode) {
      return;
    }

    container.parentNode.insertBefore(alertDiv, container);

    setTimeout(function () {
      alertDiv.remove();
    }, 3000);
  }

  document.querySelectorAll('.status-select').forEach(function (select) {
    select.addEventListener('change', async function (e) {
      const id = e.target.dataset.id;
      const status = e.target.value;
      const previousStatus = status === 'publish' ? 'draft' : 'publish';

      try {
        const response = await fetch(publishBaseUrl + '/' + id + '/publish', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: 'status=' + encodeURIComponent(status) + '&csrf_token=' + encodeURIComponent(csrfToken)
        });

        if (!response.ok) {
          showFlash('Failed to update status', 'danger');
          e.target.value = previousStatus;
        } else {
          const statusText = status === 'publish' ? 'Published' : 'Draft';
          showFlash('Status updated to ' + statusText);
        }
      } catch (error) {
        console.error('Error:', error);
        showFlash('Error updating status', 'danger');
        e.target.value = previousStatus;
      }
    });
  });

  const programmeSearch = document.getElementById('programmeSearch');
  const programmeLevelFilter = document.getElementById('programmeLevelFilter');
  const programmeRows = Array.from(document.querySelectorAll('#programmesTable tbody .programme-row'));
  const noProgrammeResults = document.getElementById('noProgrammeResults');

  const filterProgrammes = function () {
    if (!noProgrammeResults) {
      return;
    }

    const query = programmeSearch ? programmeSearch.value.trim().toLowerCase() : '';
    const selectedLevel = programmeLevelFilter ? programmeLevelFilter.value.trim().toLowerCase() : '';
    let visibleCount = 0;

    programmeRows.forEach(function (row) {
      const titleLink = row.querySelector('td:first-child a');
      const titleText = (titleLink ? titleLink.textContent : row.textContent).trim().toLowerCase();
      const rowLevel = (row.dataset.level || '').trim().toLowerCase();
      const matchesQuery = query === '' || titleText.includes(query);
      const matchesLevel = selectedLevel === '' || rowLevel === selectedLevel;
      const matches = matchesQuery && matchesLevel;
      row.classList.toggle('d-none', !matches);
      if (matches) {
        visibleCount += 1;
      }
    });

    noProgrammeResults.classList.toggle('d-none', visibleCount > 0);
  };

  if (programmeSearch) {
    programmeSearch.addEventListener('input', filterProgrammes);
  }

  if (programmeLevelFilter) {
    programmeLevelFilter.addEventListener('change', filterProgrammes);
  }
})();
