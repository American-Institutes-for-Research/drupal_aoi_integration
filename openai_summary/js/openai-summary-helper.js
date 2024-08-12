(function ($, Drupal) {
  Drupal.behaviors.openaiSummaryHelper = {
    attach: function (context) {
      once(
        "openai-summary-helper",
        '[data-drupal-selector="edit-body-wrapper"]',
        context
      ).forEach((el) => {

        const showThrobber = () => {
          const wrapper = document.querySelector(
            `[data-drupal-selector="edit-body-wrapper"]`
          );
          const existingThrobber = document.querySelector(".ajax-progress");
          if (existingThrobber) {
            wrapper.parentElement.appendChild(existingThrobber);
          } else {
            const throbber = document.createElement("div");
            throbber.classList.add("ajax-progress", "ajax-progress--throbber");
            throbber.innerHTML = `
              <div class="ajax-progress__throbber">
              </div>
              <div class="ajax-progress__message">
              Please wait...</div>`;
            wrapper.parentElement.appendChild(throbber);
          }
        };

        const removeThrobber = () => {
          const throbberElements = document.querySelectorAll(".ajax-progress");
          throbberElements.forEach((element) => {
            element.remove();
          });
        };

        const simulateClick = (element) => {
          const event = new MouseEvent("click", {
            bubbles: true,
            cancelable: true,
            view: window,
          });
          element.dispatchEvent(event);
          showThrobber();
        };

        removeThrobber();

        const buttonElement = document.querySelector(".openai-summary-btn");
        const submitBtn = document.querySelector(
          '[data-drupal-selector="edit-fill-summary"]'
        );
        submitBtn.classList.add("visually-hidden");
        buttonElement.addEventListener("click", () => {
          simulateClick(submitBtn);
        });

        $(document).ajaxError(function () {
          removeThrobber();
        });

        $(document).ajaxComplete(function () {
          removeThrobber();
          const buttonElement = document.querySelector(".openai-summary-btn");
          buttonElement.addEventListener("click", () => {
            simulateClick(submitBtn);
          });
        });
      });
    },
  };
})(jQuery, Drupal);
