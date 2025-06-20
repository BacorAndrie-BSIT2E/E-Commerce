<script>
  function openSignup() {
    document.getElementById("signupModal").style.display = "block";
  }

  function closeSignup() {
    document.getElementById("signupModal").style.display = "none";
  }

  // Close modal when clicking outside of it
  window.onclick = function(event) {
    const modal = document.getElementById("signupModal");
    if (event.target == modal) {
      modal.style.display = "none";
    }
  }
</script>
