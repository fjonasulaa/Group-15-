(function () {
  const SYSTEM_PROMPT = `You are "Edward", a friendly and knowledgeable AI chatbot for the Wine Exchange website. You help users with:

- Wine recommendations (red, white, rosé, sparkling, by budget, occasion, or taste)
- Food and wine pairings
- How the wine exchange works (users can buy and sell wines, list bottles, browse listings)
- Returns and refunds policy: customers can return unopened bottles within 14 days of delivery for a full refund. Damaged or faulty wine is covered and can be returned at any time with evidence. Returns are not accepted for opened bottles unless the wine is corked/faulty.
- Selling wine: users can list their bottles on the platform, set a price, and the exchange takes a small commission (typically 5-10%). Wines must be properly stored and described accurately.
- Shipping: wines are shipped in protective packaging. Standard delivery is 3-5 working days. Express next-day delivery is available. Temperature-sensitive wines may be held during extreme weather.
- Wine storage: wines should be stored on their side at 12-14°C, away from light. The exchange can also offer bonded storage.
- General wine education: regions, grapes, tasting notes, vintages, serving temperatures

Keep responses warm, knowledgeable, and concise (2-4 sentences max unless a list is helpful). Use light wine-related language naturally. Never make up specific prices or listings — tell users to browse the website for live stock.`;

  const QUICK_CHIPS = [
    { label: "How it works",       msg: "How does the wine exchange work?" },
    { label: "Returns policy",     msg: "What is your returns policy?" },
    { label: "Recommend a wine",   msg: "Can you recommend a red wine under £30?" },
    { label: "Food pairings",      msg: "What food pairs well with Pinot Noir?" },
    { label: "Sell my wine",       msg: "How do I sell my wine on the exchange?" },
    { label: "Shipping & storage", msg: "How is wine stored and shipped?" },
  ];

  // ── Inject styles ──────────────────────────────────────────────────────────
  const style = document.createElement("style");
  style.textContent = `
    @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;1,400&family=DM+Sans:wght@300;400;500&display=swap');

    #wc-toggle {
      position: fixed;
      bottom: 24px;
      right: 24px;
      width: 56px;
      height: 56px;
      border-radius: 50%;
      background: #7B1F2E;
      border: none;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 4px 16px rgba(123,31,46,0.35);
      z-index: 9998;
      transition: transform 0.2s, opacity 0.2s;
      font-size: 24px;
    }
    #wc-toggle:hover { transform: scale(1.08); }

    #wc-window {
      position: fixed;
      bottom: 92px;
      right: 24px;
      width: 360px;
      height: 560px;
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 8px 40px rgba(0,0,0,0.18);
      display: flex;
      flex-direction: column;
      overflow: hidden;
      z-index: 9999;
      font-family: 'DM Sans', sans-serif;
      transition: opacity 0.2s, transform 0.2s;
      transform-origin: bottom right;
    }
    #wc-window.wc-hidden {
      opacity: 0;
      transform: scale(0.92);
      pointer-events: none;
    }

    .wc-header {
      background: #7B1F2E;
      padding: 14px 16px;
      display: flex;
      align-items: center;
      gap: 10px;
      flex-shrink: 0;
    }
    .wc-header-icon {
      width: 34px; height: 34px;
      background: rgba(255,255,255,0.15);
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-size: 16px;
    }
    .wc-header h3 {
      font-family: 'Cormorant Garamond', serif;
      color: #fff; font-size: 16px; font-weight: 500;
      margin: 0; letter-spacing: 0.3px;
    }
    .wc-header p {
      color: rgba(255,255,255,0.65); font-size: 11px;
      margin: 0; font-weight: 300;
    }
    .wc-dot {
      width: 7px; height: 7px;
      background: #5DCAA5; border-radius: 50%;
      display: inline-block; margin-right: 3px;
    }
    .wc-close {
      margin-left: auto;
      background: none; border: none;
      color: rgba(255,255,255,0.7);
      cursor: pointer; font-size: 20px; line-height: 1;
      padding: 0 4px;
    }
    .wc-close:hover { color: #fff; }

    .wc-chips {
      display: flex; gap: 6px;
      padding: 9px 12px;
      background: #faf8f6;
      border-bottom: 1px solid #ede8e4;
      overflow-x: auto; scrollbar-width: none;
      flex-shrink: 0;
    }
    .wc-chips::-webkit-scrollbar { display: none; }
    .wc-chip {
      white-space: nowrap; font-size: 11.5px;
      padding: 5px 10px; border-radius: 20px;
      border: 1px solid #d8cfc9;
      background: #fff; color: #6b5b53;
      cursor: pointer; font-family: 'DM Sans', sans-serif;
      transition: all 0.15s;
    }
    .wc-chip:hover { border-color: #7B1F2E; color: #7B1F2E; background: #f5ebe8; }

    .wc-messages {
      flex: 1; overflow-y: auto;
      padding: 14px 12px;
      display: flex; flex-direction: column; gap: 10px;
      scrollbar-width: thin; scrollbar-color: #ddd transparent;
    }
    .wc-msg { display: flex; gap: 7px; align-items: flex-end; }
    .wc-msg.wc-user { flex-direction: row-reverse; }
    .wc-avatar {
      width: 26px; height: 26px; border-radius: 50%;
      background: #7B1F2E;
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0; overflow: hidden;
    }
    .wc-bubble {
      max-width: 80%; padding: 9px 12px;
      border-radius: 14px; font-size: 13.5px;
      line-height: 1.55; color: #2c1a14;
      background: #f5f0ed; border: 1px solid #ede8e4;
    }
    .wc-msg.wc-bot .wc-bubble { border-bottom-left-radius: 3px; }
    .wc-msg.wc-user .wc-bubble {
      background: #7B1F2E; color: #fff;
      border-color: transparent;
      border-bottom-right-radius: 3px;
    }
    .wc-typing {
      display: flex; gap: 4px; padding: 10px 12px;
    }
    .wc-typing span {
      width: 6px; height: 6px;
      background: #b8a09a; border-radius: 50%;
      animation: wc-bounce 1.2s infinite;
    }
    .wc-typing span:nth-child(2) { animation-delay: 0.2s; }
    .wc-typing span:nth-child(3) { animation-delay: 0.4s; }
    @keyframes wc-bounce {
      0%,60%,100% { transform: translateY(0); opacity: 0.4; }
      30% { transform: translateY(-5px); opacity: 1; }
    }

    .wc-input-row {
      display: flex; gap: 8px;
      padding: 10px 12px;
      border-top: 1px solid #ede8e4;
      background: #fff; flex-shrink: 0;
    }
    .wc-input {
      flex: 1; border: 1px solid #d8cfc9;
      border-radius: 20px; padding: 8px 13px;
      font-size: 13.5px; font-family: 'DM Sans', sans-serif;
      background: #faf8f6; color: #2c1a14;
      outline: none; resize: none; line-height: 1.4;
    }
    .wc-input:focus { border-color: #C4607A; box-shadow: 0 0 0 2px rgba(123,31,46,0.1); }
    .wc-send {
      width: 36px; height: 36px; border-radius: 50%;
      background: #7B1F2E; border: none;
      cursor: pointer; display: flex;
      align-items: center; justify-content: center;
      flex-shrink: 0; transition: opacity 0.15s;
    }
    .wc-send:hover { opacity: 0.85; }
    .wc-send:active { transform: scale(0.95); }

    @media (max-width: 420px) {
      #wc-window { width: calc(100vw - 20px); right: 10px; bottom: 80px; }
    }
  `;
  document.head.appendChild(style);

  // ── Build DOM ──────────────────────────────────────────────────────────────
  const toggle = document.createElement("button");
  toggle.id = "wc-toggle";
  toggle.innerHTML = `<img src="../../images/chatbot-logo.png" alt="Edward" style="width:34px;height:34px;object-fit:contain;border-radius:50%;">`;
  toggle.title = "Chat with Edward";
  document.body.appendChild(toggle);

  const win = document.createElement("div");
  win.id = "wc-window";
  win.classList.add("wc-hidden");
  win.innerHTML = `
    <div class="wc-header">
      <div class="wc-header-icon"><img src="../../images/chatbot-logo.png" alt="Wine Exchange" style="width:26px;height:26px;object-fit:contain;border-radius:50%;"></div>
      <div>
        <h3>Edward</h3>
        <p><span class="wc-dot"></span>Wine Exchange — always available</p>
      </div>
      <button class="wc-close" id="wc-close-btn" title="Close">&#x2715;</button>
    </div>
    <div class="wc-chips" id="wc-chips"></div>
    <div class="wc-messages" id="wc-messages">
      <div class="wc-msg wc-bot">
        <div class="wc-avatar"><img src="../../images/chatbot-logo.png" alt="Edward" style="width:26px;height:26px;object-fit:contain;border-radius:50%;"></div>
        <div class="wc-bubble">Welcome to Wine Exchange! 🍷<br><br>I'm Edward, your personal wine assistant. Ask me anything — recommendations, how the exchange works, returns, food pairings, or selling your bottles. How can I help?</div>
      </div>
    </div>
    <div class="wc-input-row">
      <textarea class="wc-input" id="wc-input" placeholder="Ask about wines, returns, how to sell…" rows="1"></textarea>
      <button class="wc-send" id="wc-send">
        <svg width="16" height="16" viewBox="0 0 20 20" fill="none">
          <path d="M3 10L17 3L10 17L9 11L3 10Z" fill="white" stroke="white" stroke-width="1" stroke-linejoin="round"/>
        </svg>
      </button>
    </div>
  `;
  document.body.appendChild(win);

  // ── Chips ──────────────────────────────────────────────────────────────────
  const chipsEl = document.getElementById("wc-chips");
  QUICK_CHIPS.forEach(({ label, msg }) => {
    const btn = document.createElement("button");
    btn.className = "wc-chip";
    btn.textContent = label;
    btn.onclick = () => send(msg);
    chipsEl.appendChild(btn);
  });

  // ── Toggle open/close ──────────────────────────────────────────────────────
  toggle.addEventListener("click", () => win.classList.toggle("wc-hidden"));
  document.getElementById("wc-close-btn").addEventListener("click", () => win.classList.add("wc-hidden"));

  // ── Chat logic ─────────────────────────────────────────────────────────────
  const messagesEl = document.getElementById("wc-messages");
  const inputEl    = document.getElementById("wc-input");
  const sendBtn    = document.getElementById("wc-send");
  const history    = [];

  function addMessage(role, html) {
    const div = document.createElement("div");
    div.className = "wc-msg wc-" + role;
    div.innerHTML = role === "bot"
      ? `<div class="wc-avatar"><img src="../../images/chatbot-logo.png" alt="Edward" style="width:26px;height:26px;object-fit:contain;border-radius:50%;"></div><div class="wc-bubble">${html}</div>`
      : `<div class="wc-bubble">${html}</div>`;
    messagesEl.appendChild(div);
    messagesEl.scrollTop = messagesEl.scrollHeight;
  }

  function showTyping() {
    const div = document.createElement("div");
    div.className = "wc-msg wc-bot"; div.id = "wc-typing";
    div.innerHTML = `<div class="wc-avatar"><img src="../../images/chatbot-logo.png" alt="Edward" style="width:26px;height:26px;object-fit:contain;border-radius:50%;"></div><div class="wc-bubble wc-typing"><span></span><span></span><span></span></div>`;
    messagesEl.appendChild(div);
    messagesEl.scrollTop = messagesEl.scrollHeight;
  }

  function removeTyping() {
    const t = document.getElementById("wc-typing");
    if (t) t.remove();
  }

  function escHtml(s) {
    return s.replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;");
  }

  function fmt(text) {
    return escHtml(text)
      .replace(/\*\*(.*?)\*\*/g, "<strong>$1</strong>")
      .replace(/\n\n/g, "<br><br>")
      .replace(/\n/g, "<br>");
  }

  async function callClaude(userMsg) {
    history.push({ role: "user", content: userMsg });
    const res = await fetch("chatbot-proxy.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        model: "claude-sonnet-4-20250514",
        max_tokens: 1000,
        system: SYSTEM_PROMPT,
        messages: history
      })
    });
    const data = await res.json();
    const text = data.content?.find(b => b.type === "text")?.text
      || "Sorry, I couldn't get a response — please try again!";
    history.push({ role: "assistant", content: text });
    return text;
  }

  async function send(text) {
    text = text.trim();
    if (!text) return;
    addMessage("user", escHtml(text));
    inputEl.value = ""; inputEl.style.height = "auto";
    showTyping();
    try {
      const reply = await callClaude(text);
      removeTyping();
      addMessage("bot", fmt(reply));
    } catch (e) {
      removeTyping();
      addMessage("bot", "Sorry, I'm having trouble connecting right now. Please try again shortly.");
    }
  }

  sendBtn.addEventListener("click", () => send(inputEl.value));
  inputEl.addEventListener("keydown", e => {
    if (e.key === "Enter" && !e.shiftKey) { e.preventDefault(); send(inputEl.value); }
  });
  inputEl.addEventListener("input", function () {
    this.style.height = "auto";
    this.style.height = Math.min(this.scrollHeight, 80) + "px";
  });
})();