<?php
session_start();
require_once('../../database/db_connect.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Wine Gift Finder | Wine Exchange</title>
  <link rel="icon" type="image/x-icon" href="../../images/icon.png">
  <link rel="stylesheet" href="../css/styles.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>

<body class="info">

  <?php include 'header.php'; ?>

  <div class="gq-page">

    <!-- Intro screen -->
    <div class="gq-screen active" id="screen-intro">
      <div class="gq-intro-bg"></div>
      <div class="gq-intro-content">
        <div class="gq-gift-icon"><i class="fas fa-wine-bottle"></i></div>
        <p class="gq-eyebrow">Wine Exchange</p>
        <h1 class="gq-intro-title">Find the<br><em>Perfect</em> Gift</h1>
        <p class="gq-intro-sub">Answer 5 quick questions and we'll hand-pick a wine gift tailored to your recipient.</p>
        <button class="gq-btn-primary" onclick="startQuiz()">
          Begin <i class="fas fa-arrow-right"></i>
        </button>
      </div>
    </div>

    <!-- Quiz screen -->
    <div class="gq-screen" id="screen-quiz">
      <div class="gq-quiz-wrap">
        <div class="gq-progress-bar">
          <div class="gq-progress-fill" id="progressFill"></div>
        </div>
        <div class="gq-step-label" id="stepLabel">Question 1 of 5</div>
        <div class="gq-question-area" id="questionArea"></div>
        <div class="gq-nav">
          <button class="gq-btn-ghost" id="backBtn" onclick="goBack()" style="visibility:hidden">
            <i class="fas fa-arrow-left"></i> Back
          </button>
          <button class="gq-btn-primary" id="nextBtn" onclick="goNext()" disabled>
            Next <i class="fas fa-arrow-right"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Results screen -->
    <div class="gq-screen" id="screen-results">
      <div class="gq-results-wrap">
        <div class="gq-results-header">
          <i class="fas fa-star gq-star-icon"></i>
          <h2>Your Perfect Picks</h2>
          <p id="resultsSubtitle">Based on your answers, here are our top recommendations.</p>
        </div>
        <div class="gq-results-grid" id="resultsGrid"></div>
        <div class="gq-results-footer">
          <button class="gq-btn-ghost" onclick="resetQuiz()">
            <i class="fas fa-redo"></i> Start Over
          </button>
          <a href="search.php" class="gq-btn-primary">
            Browse All Wines <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>
    </div>

  </div>

  <?php include 'footer.php'; ?>

  <style>
  .gq-page { min-height: calc(100vh - 70px); font-family: 'Jost', sans-serif; position: relative; }
  .gq-screen { display: none; min-height: calc(100vh - 70px); }
  .gq-screen.active { display: flex; align-items: center; justify-content: center; }
  #screen-intro { position: relative; }

  .gq-intro-bg {
    position: absolute; inset: 0; z-index: 0;
    background: linear-gradient(135deg, #2a0a14 0%, #4a0e24 40%, #7b1e3a 70%, #9e2d4f 100%);
  }
  .gq-intro-bg::after {
    content: ''; position: absolute; inset: 0;
    background-image:
      radial-gradient(circle at 15% 85%, rgba(255,255,255,0.04) 0%, transparent 45%),
      radial-gradient(circle at 85% 15%, rgba(255,200,180,0.07) 0%, transparent 45%),
      url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none'%3E%3Cg fill='%23ffffff' fill-opacity='0.02'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
  }
  .gq-intro-content {
    position: relative; z-index: 1; text-align: center;
    padding: 40px 24px; max-width: 520px; animation: gqFadeUp 0.8s ease both;
  }
  @keyframes gqFadeUp {
    from { opacity: 0; transform: translateY(28px); }
    to   { opacity: 1; transform: translateY(0); }
  }
  .gq-gift-icon {
    width: 72px; height: 72px; border-radius: 50%;
    background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.2);
    display: flex; align-items: center; justify-content: center;
    font-size: 28px; color: #fff; margin: 0 auto 24px; backdrop-filter: blur(8px);
  }
  .gq-eyebrow { font-size: 11px; font-weight: 600; letter-spacing: 3.5px; text-transform: uppercase; color: rgba(255,255,255,0.55); margin: 0 0 14px; display: block; }
  .gq-intro-title { font-family: 'Cormorant Garamond', serif; font-size: clamp(44px, 8vw, 68px); font-weight: 600; color: #fff; line-height: 1.1; margin: 0 0 20px; }
  .gq-intro-title em { font-style: italic; color: #f4b8c8; }
  .gq-intro-sub { font-size: 15px; color: rgba(255,255,255,0.72); line-height: 1.65; margin: 0 0 36px; font-weight: 300; }

  #screen-quiz { background: #faf7f8; padding: 60px 20px; align-items: flex-start; }
  html.darkmode #screen-quiz { background: #130609; }
  .gq-quiz-wrap { width: 100%; max-width: 680px; margin: 0 auto; }
  .gq-progress-bar { height: 4px; background: #e8dfe2; border-radius: 99px; margin-bottom: 10px; overflow: hidden; }
  html.darkmode .gq-progress-bar { background: #2e1520; }
  .gq-progress-fill { height: 100%; background: linear-gradient(90deg, #7b1e3a, #c03a60); border-radius: 99px; transition: width 0.5s cubic-bezier(.4,0,.2,1); width: 20%; }
  .gq-step-label { font-size: 12px; font-weight: 500; letter-spacing: 1.5px; text-transform: uppercase; color: #9e7080; margin-bottom: 40px; display: block; }
  html.darkmode .gq-step-label { color: #7a4558; }
  .gq-question-area { animation: gqSlideIn 0.4s ease both; }
  @keyframes gqSlideIn { from { opacity: 0; transform: translateX(24px); } to { opacity: 1; transform: translateX(0); } }
  .gq-question-title { font-family: 'Cormorant Garamond', serif; font-size: clamp(26px, 4vw, 36px); font-weight: 600; color: #2a0a14; margin: 0 0 8px; line-height: 1.25; }
  html.darkmode .gq-question-title { color: #f5e8ec; }
  .gq-question-hint { font-size: 14px; color: #9e7080; margin: 0 0 32px; font-weight: 300; }

  .gq-options { display: grid; gap: 12px; }
  .gq-options.cols-2 { grid-template-columns: 1fr 1fr; }
  .gq-options.cols-1 { grid-template-columns: 1fr; }
  @media (max-width: 500px) { .gq-options.cols-2 { grid-template-columns: 1fr; } }

  .gq-option { display: flex; align-items: center; gap: 14px; padding: 16px 20px; border: 1.5px solid #e8dfe2; border-radius: 14px; background: #fff; cursor: pointer; transition: all 0.2s ease; position: relative; overflow: hidden; user-select: none; }
  html.darkmode .gq-option { background: #1e0a11; border-color: #3a1525; }
  .gq-option:hover { border-color: #7b1e3a; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(123,30,58,0.1); }
  .gq-option.selected { border-color: #7b1e3a; background: #fdf0f4; box-shadow: 0 0 0 3px rgba(123,30,58,0.1); }
  html.darkmode .gq-option.selected { background: #2e0f1a; border-color: #c03a60; box-shadow: 0 0 0 3px rgba(192,58,96,0.15); }
  .gq-option.selected::after { content: '\f00c'; font-family: 'Font Awesome 6 Free'; font-weight: 900; position: absolute; top: 10px; right: 14px; font-size: 11px; color: #7b1e3a; }
  html.darkmode .gq-option.selected::after { color: #e88ca0; }
  .gq-option-icon { font-size: 22px; flex-shrink: 0; width: 40px; height: 40px; border-radius: 10px; background: #fdf0f4; display: flex; align-items: center; justify-content: center; }
  html.darkmode .gq-option-icon { background: #3a1525; }
  .gq-option-text strong { display: block; font-size: 14.5px; font-weight: 600; color: #2a0a14; margin-bottom: 2px; }
  html.darkmode .gq-option-text strong { color: #f0dde3; }
  .gq-option-text span { font-size: 12.5px; color: #9e7080; font-weight: 300; }

  .gq-budget-wrap { margin-top: 8px; }
  .gq-budget-display { font-family: 'Cormorant Garamond', serif; font-size: 52px; font-weight: 600; color: #7b1e3a; text-align: center; margin-bottom: 16px; line-height: 1; }
  html.darkmode .gq-budget-display { color: #e88ca0; }
  .gq-budget-display span { font-size: 26px; vertical-align: super; }
  .gq-slider { -webkit-appearance: none; appearance: none; width: 100%; height: 6px; border-radius: 99px; outline: none; cursor: pointer; background: linear-gradient(90deg, #7b1e3a var(--pct, 33%), #e8dfe2 var(--pct, 33%)); }
  html.darkmode .gq-slider { background: linear-gradient(90deg, #c03a60 var(--pct, 33%), #2e1520 var(--pct, 33%)); }
  .gq-slider::-webkit-slider-thumb { -webkit-appearance: none; width: 22px; height: 22px; border-radius: 50%; background: #7b1e3a; border: 3px solid #fff; box-shadow: 0 2px 8px rgba(123,30,58,0.35); cursor: pointer; }
  .gq-slider-labels { display: flex; justify-content: space-between; font-size: 12px; color: #9e7080; margin-top: 10px; }
  html.darkmode .gq-slider-labels { color: #7a4558; }
  .gq-nav { display: flex; justify-content: space-between; align-items: center; margin-top: 44px; }

  .gq-btn-primary { display: inline-flex; align-items: center; gap: 10px; background: #7b1e3a; color: #fff; border: none; border-radius: 12px; padding: 14px 30px; font-family: 'Jost', sans-serif; font-size: 15px; font-weight: 600; cursor: pointer; transition: background 0.2s, transform 0.15s, box-shadow 0.2s; text-decoration: none; box-shadow: 0 4px 16px rgba(123,30,58,0.28); letter-spacing: 0.2px; }
  .gq-btn-primary:hover { background: #5e152c; transform: translateY(-2px); box-shadow: 0 8px 24px rgba(123,30,58,0.38); color: #fff; }
  .gq-btn-primary:disabled { background: #c8a0ac; box-shadow: none; cursor: not-allowed; transform: none; }
  .gq-btn-ghost { display: inline-flex; align-items: center; gap: 8px; background: transparent; color: #7b1e3a; border: 1.5px solid #e8dfe2; border-radius: 12px; padding: 13px 24px; font-family: 'Jost', sans-serif; font-size: 14px; font-weight: 500; cursor: pointer; transition: all 0.2s; text-decoration: none; }
  .gq-btn-ghost:hover { border-color: #7b1e3a; background: #fdf0f4; }
  html.darkmode .gq-btn-ghost { color: #e88ca0; border-color: #3a1525; }
  html.darkmode .gq-btn-ghost:hover { background: #2e0f1a; border-color: #c03a60; }

  #screen-results { background: #faf7f8; padding: 60px 20px; align-items: flex-start; }
  html.darkmode #screen-results { background: #130609; }
  .gq-results-wrap { width: 100%; max-width: 1060px; margin: 0 auto; animation: gqFadeUp 0.6s ease both; }
  .gq-results-header { text-align: center; margin-bottom: 48px; }
  .gq-star-icon { font-size: 28px; color: #c9a84c; margin-bottom: 14px; display: block; }
  .gq-results-header h2 { font-family: 'Cormorant Garamond', serif; font-size: clamp(32px, 5vw, 48px); font-weight: 600; color: #2a0a14; margin: 0 0 10px; }
  html.darkmode .gq-results-header h2 { color: #f5e8ec; }
  .gq-results-header p { font-size: 15px; color: #9e7080; margin: 0; font-weight: 300; }
  .gq-results-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 24px; margin-bottom: 48px; }
  .gq-wine-card { background: #fff; border-radius: 18px; border: 1px solid #ece8e9; overflow: hidden; box-shadow: 0 2px 20px rgba(123,30,58,0.06); transition: transform 0.2s, box-shadow 0.2s; animation: gqFadeUp 0.5s ease both; }
  .gq-wine-card:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(123,30,58,0.12); }
  html.darkmode .gq-wine-card { background: #1e0a11; border-color: #3a1525; box-shadow: 0 2px 20px rgba(0,0,0,0.3); }
  .gq-wine-img-wrap { height: 200px; background: linear-gradient(135deg, #fdf0f4, #f5e0e8); display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative; }
  html.darkmode .gq-wine-img-wrap { background: linear-gradient(135deg, #2e0f1a, #1e0a11); }
  .gq-wine-img-wrap img { width: 100%; height: 100%; object-fit: cover; }
  .gq-wine-img-placeholder { font-size: 48px; opacity: 0.3; }
  .gq-match-badge { position: absolute; top: 12px; right: 12px; background: #7b1e3a; color: #fff; font-size: 11px; font-weight: 600; letter-spacing: 0.5px; padding: 4px 10px; border-radius: 99px; }
  .gq-wine-body { padding: 20px 22px 22px; }
  .gq-wine-type { font-size: 11px; font-weight: 600; letter-spacing: 2px; text-transform: uppercase; color: #9e7080; margin-bottom: 6px; }
  .gq-wine-name { font-family: 'Cormorant Garamond', serif; font-size: 20px; font-weight: 600; color: #2a0a14; margin: 0 0 6px; line-height: 1.25; }
  html.darkmode .gq-wine-name { color: #f5e8ec; }
  .gq-wine-region { font-size: 13px; color: #9e7080; margin-bottom: 14px; font-weight: 300; }
  .gq-wine-footer { display: flex; align-items: center; justify-content: space-between; }
  .gq-wine-price { font-size: 20px; font-weight: 700; color: #7b1e3a; font-family: 'Cormorant Garamond', serif; }
  html.darkmode .gq-wine-price { color: #e88ca0; }
  .gq-wine-add { background: #7b1e3a; color: #fff; border: none; border-radius: 9px; padding: 8px 16px; font-size: 13px; font-weight: 600; cursor: pointer; font-family: 'Jost', sans-serif; transition: background 0.2s, transform 0.15s; text-decoration: none; display: inline-block; }
  .gq-wine-add:hover { background: #5e152c; transform: translateY(-1px); color: #fff; }
  .gq-results-footer { display: flex; gap: 16px; justify-content: center; flex-wrap: wrap; }
  .gq-no-results { text-align: center; padding: 60px 20px; color: #9e7080; grid-column: 1/-1; }
  .gq-no-results i { font-size: 40px; margin-bottom: 16px; display: block; }
  @media (max-width: 600px) { .gq-results-grid { grid-template-columns: 1fr; } .gq-quiz-wrap { padding: 0 4px; } }
  </style>

  <script>
  const questions = [
    {
      id: "recipient", title: "Who are you gifting to?",
      hint: "We'll tailor the style and presentation accordingly.",
      type: "single", cols: 2,
      options: [
        { value:"partner",   icon:"💑", label:"Partner / Spouse",    desc:"Romantic & indulgent" },
        { value:"friend",    icon:"🥂", label:"Friend",              desc:"Fun & approachable" },
        { value:"family",    icon:"🏡", label:"Family Member",       desc:"Warm & classic" },
        { value:"colleague", icon:"💼", label:"Colleague / Boss",    desc:"Refined & professional" },
      ]
    },
    {
      id: "occasion", title: "What's the occasion?",
      hint: "Helps us pick the right mood and label.",
      type: "single", cols: 2,
      options: [
        { value:"birthday",    icon:"🎂", label:"Birthday",     desc:"Celebrate in style" },
        { value:"anniversary", icon:"💍", label:"Anniversary",  desc:"Something special" },
        { value:"christmas",   icon:"🎄", label:"Christmas",    desc:"Festive & warming" },
        { value:"thankyou",    icon:"🙏", label:"Thank You",    desc:"Thoughtful gesture" },
      ]
    },
    {
      id: "preference", title: "What do they usually enjoy?",
      hint: "Don't worry if you're not sure — pick what sounds closest.",
      type: "single", cols: 2,
      options: [
        { value:"red",       icon:"🍷", label:"Red Wine",              desc:"Bold & full-bodied" },
        { value:"white",     icon:"🥂", label:"White Wine",            desc:"Crisp & refreshing" },
        { value:"sparkling", icon:"✨", label:"Sparkling / Champagne", desc:"Celebratory & elegant" },
        { value:"rose",      icon:"🌸", label:"Rosé",                  desc:"Light & fruity" },
        { value:"unknown",   icon:"🤔", label:"Not Sure",              desc:"We'll pick the best match" },
      ]
    },
    {
      id: "experience", title: "How adventurous are they with wine?",
      hint: "We'll match the complexity to their palate.",
      type: "single", cols: 1,
      options: [
        { value:"beginner",     icon:"🌱", label:"Casual Drinker",      desc:"Keeps it simple and approachable" },
        { value:"intermediate", icon:"🍇", label:"Regular Enthusiast",  desc:"Appreciates a quality bottle" },
        { value:"expert",       icon:"🏆", label:"Serious Connoisseur", desc:"Seeks rare and complex wines" },
      ]
    },
    {
      id: "budget", title: "What's your budget?",
      hint: "Slide to set your maximum spend.",
      type: "slider", min: 10, max: 1000, default: 100, step: 5
    }
  ];

  let currentQ = 0;
  let answers  = {};

  function showScreen(id) {
    document.querySelectorAll(".gq-screen").forEach(s => { s.classList.remove("active"); s.style.display = "none"; });
    const el = document.getElementById(id);
    el.style.display = "flex"; el.classList.add("active");
  }

  function startQuiz() { currentQ = 0; answers = {}; showScreen("screen-quiz"); renderQuestion(); }

  function renderQuestion() {
    const q = questions[currentQ];
    const area = document.getElementById("questionArea");
    const nextBtn = document.getElementById("nextBtn");
    const pct = ((currentQ + 1) / questions.length) * 100;
    document.getElementById("progressFill").style.width = pct + "%";
    document.getElementById("stepLabel").textContent = `Question ${currentQ + 1} of ${questions.length}`;
    document.getElementById("backBtn").style.visibility = currentQ === 0 ? "hidden" : "visible";
    nextBtn.innerHTML = currentQ === questions.length - 1
      ? 'See My Picks <i class="fas fa-star"></i>'
      : 'Next <i class="fas fa-arrow-right"></i>';
    const saved = answers[q.id];
    nextBtn.disabled = saved === undefined;
    let html = `<h2 class="gq-question-title">${q.title}</h2><p class="gq-question-hint">${q.hint}</p>`;
    if (q.type === "slider") {
      const val   = saved !== undefined ? saved : q.default;
      const pctSl = ((val - q.min) / (q.max - q.min)) * 100;
      html += `
        <div class="gq-budget-wrap">
          <div class="gq-budget-display" id="budgetDisplay"><span>£</span>${val}</div>
          <input type="range" class="gq-slider" id="budgetSlider"
            min="${q.min}" max="${q.max}" step="${q.step}" value="${val}"
            style="--pct:${pctSl}%" oninput="onSlider(this)">
          <div class="gq-slider-labels"><span>£${q.min}</span><span>£${q.max}+</span></div>
        </div>`;
      nextBtn.disabled = false;
      if (saved === undefined) answers[q.id] = val;
    } else {
      html += `<div class="gq-options cols-${q.cols}">`;
      q.options.forEach(opt => {
        const sel = saved === opt.value ? " selected" : "";
        html += `<div class="gq-option${sel}" onclick="selectOption(this,'${q.id}','${opt.value}')"><div class="gq-option-icon">${opt.icon}</div><div class="gq-option-text"><strong>${opt.label}</strong><span>${opt.desc}</span></div></div>`;
      });
      html += `</div>`;
    }
    area.innerHTML = html;
    area.style.animation = "none"; area.offsetHeight; area.style.animation = "";
  }

  function onSlider(el) {
    const q = questions[currentQ];
    const val = parseInt(el.value);
    const pct = ((val - q.min) / (q.max - q.min)) * 100;
    el.style.setProperty("--pct", pct + "%");
    document.getElementById("budgetDisplay").innerHTML = `<span>£</span>${val}`;
    answers[q.id] = val;
    document.getElementById("nextBtn").disabled = false;
  }

  function selectOption(el, qId, value) {
    el.closest(".gq-options").querySelectorAll(".gq-option").forEach(o => o.classList.remove("selected"));
    el.classList.add("selected");
    answers[qId] = value;
    document.getElementById("nextBtn").disabled = false;
  }

  function goNext() {
    if (currentQ < questions.length - 1) { currentQ++; renderQuestion(); }
    else { showResults(); }
  }

  function goBack() { if (currentQ > 0) { currentQ--; renderQuestion(); } }
  function resetQuiz() { showScreen("screen-intro"); }

  function showResults() {
    showScreen("screen-results");
    const budget     = answers.budget     || 100;
    const preference = answers.preference || "unknown";
    const experience = answers.experience || "beginner";
    const recipient  = answers.recipient  || "friend";
    const occasion   = answers.occasion   || "birthday";

    const rLabel = { partner:"your partner", friend:"your friend", family:"your family member", colleague:"your colleague" };
    const oLabel = { birthday:"their birthday", anniversary:"your anniversary", christmas:"Christmas", thankyou:"a thank-you" };
    document.getElementById("resultsSubtitle").textContent =
      `Tailored for ${rLabel[recipient]||"them"} — perfect for ${oLabel[occasion]||"the occasion"}.`;

    const params = new URLSearchParams({ budget, preference, experience, recipient, occasion });
    fetch("Gift-quiz-results.php?" + params.toString())
      .then(r => r.json())
      .then(wines => renderResults(wines))
      .catch(err => { console.error("Fetch error:", err); renderResults([]); });
  }

  function renderResults(wines) {
    const grid = document.getElementById("resultsGrid");
    if (!wines || wines.length === 0) {
      grid.innerHTML = `<div class="gq-no-results"><i class="fas fa-wine-glass-alt"></i><p>No wines found for your criteria — try adjusting your budget or preferences.</p><a href="search.php" class="gq-btn-primary" style="margin-top:20px;display:inline-flex">Browse All Wines</a></div>`;
      return;
    }
    grid.innerHTML = wines.map((wine, i) => {
      const img = wine.imageUrl
        ? `<img src="../../images/${wine.imageUrl}" alt="${wine.wineName}" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">`
        : "";
      return `
        <div class="gq-wine-card" style="animation-delay:${i * 80}ms">
          <div class="gq-wine-img-wrap">
            ${img}
            <div class="gq-wine-img-placeholder" ${wine.imageUrl?'style="display:none"':''}>🍷</div>
            <div class="gq-match-badge">✦ Top Pick</div>
          </div>
          <div class="gq-wine-body">
            <div class="gq-wine-type">${wine.wineType || "Wine"}</div>
            <div class="gq-wine-name">${wine.wineName}</div>
            <div class="gq-wine-region">${wine.region || wine.country || ""}</div>
            <div class="gq-wine-footer">
              <div class="gq-wine-price">£${parseFloat(wine.price).toFixed(2)}</div>
              <a href="wineinfo.php?id=${wine.wineId}" class="gq-wine-add">View Wine</a>
            </div>
          </div>
        </div>`;
    }).join("");
  }

  showScreen("screen-intro");
  </script>

</body>
</html>