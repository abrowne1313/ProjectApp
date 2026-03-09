document.addEventListener("DOMContentLoaded", function () {
    const input = document.getElementById("live-search");
    const resultsBox = document.getElementById("search-results");

    if (!input) return;

    let timeout = null;

    input.addEventListener("keyup", function () {
        const query = input.value.trim();

        clearTimeout(timeout);

        if (query.length < 2) {
            resultsBox.innerHTML = "";
            resultsBox.style.display = "none";
            return;
        }

        timeout = setTimeout(() => {
            fetch(`/live-search?q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    if (!data.length) {
                        resultsBox.innerHTML = "";
                        resultsBox.style.display = "none";
                        return;
                    }

                    let html = "";
                    data.forEach(item => {
                        html += `
                            <div class="search-item">
                                <a href="${item.url}">
                                    <strong>${item.label}</strong>
                                    <span class="type">${item.type}</span>
                                </a>
                            </div>
                        `;
                    });

                    resultsBox.innerHTML = html;
                    resultsBox.style.display = "block";
                });
        }, 200);
    });

    // Hide results when clicking outside
    document.addEventListener("click", function (e) {
        if (!resultsBox.contains(e.target) && e.target !== input) {
            resultsBox.style.display = "none";
        }
    });
});
