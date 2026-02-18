const menuToggle = document.getElementById("menu-toggle");
const mainNav = document.getElementById("main-nav");

if (menuToggle && mainNav) {
  menuToggle.addEventListener("click", () => {
    const isOpen = mainNav.classList.toggle("is-open");
    menuToggle.setAttribute("aria-expanded", String(isOpen));
  });

  mainNav.querySelectorAll("a").forEach((link) => {
    link.addEventListener("click", () => {
      mainNav.classList.remove("is-open");
      menuToggle.setAttribute("aria-expanded", "false");
    });
  });
}

const revealItems = document.querySelectorAll(".reveal");

if ("IntersectionObserver" in window) {
  const observer = new IntersectionObserver(
    (entries, obs) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        entry.target.classList.add("is-visible");
        obs.unobserve(entry.target);
      });
    },
    { threshold: 0.17 },
  );

  revealItems.forEach((item) => observer.observe(item));
} else {
  revealItems.forEach((item) => item.classList.add("is-visible"));
}

const yearNode = document.getElementById("year");
if (yearNode) {
  yearNode.textContent = String(new Date().getFullYear());
}

const feedbackNode = document.getElementById("contact-feedback");
if (feedbackNode) {
  const params = new URLSearchParams(window.location.search);
  const contactState = params.get("contact");

  if (contactState === "success") {
    feedbackNode.textContent = "Merci, votre demande a bien ete envoyee.";
    feedbackNode.classList.add("is-success");
  } else if (contactState === "error") {
    feedbackNode.textContent =
      "Envoi impossible pour le moment. Contactez-nous par telephone au 06 61 64 03 39.";
    feedbackNode.classList.add("is-error");
  } else if (contactState === "invalid") {
    feedbackNode.textContent =
      "Formulaire incomplet. Merci de verifier votre nom, email, vehicule et projet.";
    feedbackNode.classList.add("is-error");
  } else if (contactState === "spam") {
    feedbackNode.textContent = "Demande refusee.";
    feedbackNode.classList.add("is-error");
  }
}
