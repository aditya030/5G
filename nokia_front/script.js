document.addEventListener("DOMContentLoaded", function () {
    const fetchButton = document.getElementById("fetchButton");
    fetchButton.addEventListener("click", fetchRecommendations);
});

function fetchRecommendations() {
    const faultId = document.getElementById("faultId").value;

    // Fetch recommendations using XMLHttpRequest or fetch API
    // ...

    // Fetch the image
    const image = document.getElementById("faultImage");
    image.src = `api.php?faultId=${faultId}`;
}

  