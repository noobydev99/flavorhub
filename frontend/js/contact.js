// ─── FlavorHub Contact Page Logic ─────────────────────────────

document.addEventListener("DOMContentLoaded", () => {
  setupContactForm();
  setupFAQ();
});

function setupContactForm() {
  const form = document.getElementById("contactForm");
  if (!form) return;

  form.addEventListener("submit", (e) => {
    e.preventDefault();

    const name = document.getElementById("contactName").value.trim();
    const email = document.getElementById("contactEmail").value.trim();
    const subject = document.getElementById("contactSubject").value;
    const message = document.getElementById("contactMessage").value.trim();

    let hasError = false;

    if (!name) { setFieldError("contactName", "Please enter your name."); hasError = true; } else clearFieldError("contactName");
    if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { setFieldError("contactEmail", "Please enter a valid email."); hasError = true; } else clearFieldError("contactEmail");
    if (!subject) { setFieldError("contactSubject", "Please select a subject."); hasError = true; } else clearFieldError("contactSubject");
    if (!message || message.length < 10) { setFieldError("contactMessage", "Please write a message (at least 10 characters)."); hasError = true; } else clearFieldError("contactMessage");

    if (hasError) return;

    // Simulate submission
    const submitBtn = form.querySelector(".btn-contact-submit");
    submitBtn.textContent = "Sending...";
    submitBtn.disabled = true;

    setTimeout(() => {
      submitBtn.textContent = "Send Message";
      submitBtn.disabled = false;

      // Show success message
      const successEl = document.getElementById("contactSuccessMsg");
      if (successEl) { successEl.style.display = "flex"; }

      form.reset();
      window.showToast("Message sent! We'll get back to you soon.", "success");

      setTimeout(() => { if (successEl) successEl.style.display = "none"; }, 4000);
    }, 1200);
  });
}

function setFieldError(id, msg) {
  const input = document.getElementById(id);
  if (!input) return;
  input.classList.add("error");
  let err = input.parentNode.querySelector(".field-error-msg");
  if (!err) { err = document.createElement("span"); err.className = "field-error-msg"; err.style.cssText = "color: var(--danger-red); font-size: 0.75rem; margin-top: 0.25rem; display: block;"; input.parentNode.appendChild(err); }
  err.textContent = msg;
}

function clearFieldError(id) {
  const input = document.getElementById(id);
  if (!input) return;
  input.classList.remove("error");
  const err = input.parentNode.querySelector(".field-error-msg");
  if (err) err.remove();
}

function setupFAQ() {
  const faqItems = document.querySelectorAll(".faq-item");
  faqItems.forEach(item => {
    const btn = item.querySelector(".faq-question");
    btn.addEventListener("click", () => {
      const isOpen = item.classList.contains("open");
      faqItems.forEach(f => f.classList.remove("open"));
      if (!isOpen) item.classList.add("open");
    });
  });
}

