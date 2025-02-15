/*
  applicantPage.js
  -------------------------------------------------
  Purpose:
    - Attaches click events to "رفض" and "قبول" buttons
    - Shows an alert when each is clicked
    - Can be extended for further logic (API calls, DB updates, etc.)
*/

/*
  Event: DOMContentLoaded
  Fired when the initial HTML doc is loaded and parsed
  => We then attach listeners to our buttons
*/
document.addEventListener("DOMContentLoaded", () => {
  // Query the reject/accept buttons
  const rejectBtn = document.querySelector(".reject-btn");
  const acceptBtn = document.querySelector(".accept-btn");

  /*
    If reject button is clicked:
      - Show an alert "تم رفض المتقدم."
      - Could integrate your real logic (like an API call)
  */
  if (rejectBtn) {
    rejectBtn.addEventListener("click", () => {
      alert("تم رفض المتقدم.");
    });
  }

  /*
    If accept button is clicked:
      - Show an alert "تم قبول المتقدم!"
      - Could integrate your real logic (like an API call)
  */
  if (acceptBtn) {
    acceptBtn.addEventListener("click", () => {
      alert("تم قبول المتقدم!");
    });
  }
});
