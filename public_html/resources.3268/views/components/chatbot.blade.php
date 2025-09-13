<script>
document.addEventListener("DOMContentLoaded", function() {
    const toggle = document.getElementById("chatbot-toggle");
    const chatbotWindow = document.getElementById("chatbot-window");
    const input = document.getElementById("chatbot-input");
    const sendBtn = document.getElementById("chatbot-send");
    const messages = document.getElementById("chatbot-messages");
    const suggestionsContainer = document.getElementById("chatbot-suggestions");
    
    const chatModeBtn = document.getElementById("chat-mode-btn");
    const faqModeBtn = document.getElementById("faq-mode-btn");
    const chatMode = document.getElementById("chat-mode");
    const faqMode = document.getElementById("faq-mode");
    const faqContent = document.getElementById("faq-content");
    
    let currentMode = 'chat';
    
    // فتح/إغلاق الشات
    toggle.addEventListener("click", function() {
        if (chatbotWindow.style.display === "none" || chatbotWindow.style.display === "") {
            chatbotWindow.style.display = "flex";
            if (currentMode === 'chat') {
                input.focus();
            }
        } else {
            chatbotWindow.style.display = "none";
        }
    });
    
    // تبديل الأوضاع
    chatModeBtn.addEventListener("click", () => switchMode('chat'));
    faqModeBtn.addEventListener("click", () => switchMode('faq'));
    
    function switchMode(mode) {
        currentMode = mode;
        if (mode === 'chat') {
            chatMode.style.display = 'flex';
            faqMode.style.display = 'none';
            chatModeBtn.style.background = 'rgba(255,255,255,0.4)';
            faqModeBtn.style.background = 'rgba(255,255,255,0.2)';
        } else {
            chatMode.style.display = 'none';
            faqMode.style.display = 'flex';
            chatModeBtn.style.background = 'rgba(255,255,255,0.2)';
            faqModeBtn.style.background = 'rgba(255,255,255,0.4)';
            loadFAQ();
        }
    }
    
    // تحميل الأسئلة الشائعة
    function loadFAQ() {
        fetch("https://technological-learning.online/chatbot/questions")
            .then(response => {
                if (!response.ok) throw new Error("HTTP error " + response.status);
                return response.json();
            })
            .then(questions => {
                if (!Array.isArray(questions) || questions.length === 0) {
                    faqContent.innerHTML = '<div style="text-align: center; padding: 20px; color: #666;">لا توجد أسئلة متاحة حالياً</div>';
                    return;
                }

                let faqHtml = '';
                questions.forEach((item, index) => {
                    faqHtml += `
                        <div style="margin-bottom: 10px; border: 1px solid #dee2e6; border-radius: 8px; overflow: hidden;">
                            <div class="faq-question" data-index="${index}" style="
                                background: #fff;
                                padding: 12px;
                                cursor: pointer;
                                font-weight: bold;
                                font-size: 14px;
                                border-bottom: 1px solid #eee;
                                transition: background 0.2s ease;
                            ">
                                <i class="bi bi-chevron-left" style="float: left; transition: transform 0.2s ease;"></i>
                                ${item.question}
                            </div>
                            <div class="faq-answer" data-index="${index}" style="
                                background: #f8f9fa;
                                padding: 12px;
                                font-size: 13px;
                                line-height: 1.5;
                                display: none;
                                border-top: 1px solid #eee;
                            ">
                                ${item.answer}
                            </div>
                        </div>
                    `;
                });
                
                faqContent.innerHTML = faqHtml;
                
                // إضافة أحداث فتح/إغلاق الإجابات
                document.querySelectorAll('.faq-question').forEach(question => {
                    question.addEventListener('click', function() {
                        const index = this.dataset.index;
                        const answer = document.querySelector(`.faq-answer[data-index="${index}"]`);
                        const icon = this.querySelector('.bi-chevron-left');
                        
                        if (answer.style.display === 'none') {
                            answer.style.display = 'block';
                            icon.style.transform = 'rotate(-90deg)';
                            this.style.background = '#e9ecef';
                        } else {
                            answer.style.display = 'none';
                            icon.style.transform = 'rotate(0deg)';
                            this.style.background = '#fff';
                        }
                    });
                });
            })
            .catch(error => {
                console.error("Error loading FAQ:", error);
                faqContent.innerHTML = '<div style="text-align: center; padding: 20px; color: #666;">حدث خطأ في تحميل الأسئلة الشائعة</div>';
            });
    }
    
    // تحميل وعرض التلميحات
    fetch("/chatbot-suggestions")
        .then(response => response.json())
        .then(suggestions => {
            suggestions.forEach(suggestionText => {
                const suggestionButton = document.createElement('button');
                suggestionButton.textContent = suggestionText;
                suggestionButton.style.cssText = `
                    background: #e9ecef;
                    border: 1px solid #dee2e6;
                    border-radius: 15px;
                    padding: 5px 10px;
                    font-size: 12px;
                    cursor: pointer;
                    transition: background 0.2s ease;
                `;
                suggestionButton.onmouseover = function() { this.style.background = '#d0d0d0'; };
                suggestionButton.onmouseout = function() { this.style.background = '#e9ecef'; };
                suggestionButton.onclick = function() {
                    input.value = suggestionText;
                    sendMessage();
                };
                suggestionsContainer.appendChild(suggestionButton);
            });
        })
        .catch(error => console.error("Error loading suggestions:", error));

    // إرسال الرسالة
    function sendMessage() {
        const message = input.value.trim();
        if (!message) return;
        
        addMessage(message, "user");
        input.value = "";
        
        fetch("/chatbot", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.getAttribute("content") || ""
            },
            body: JSON.stringify({message})
        })
        .then(response => response.json())
        .then(data => {
            addMessage(data.reply, "bot");
        })
        .catch(error => {
            console.error("Error:", error);
            addMessage("عذراً، حدث خطأ في الاتصال. حاول مرة أخرى.", "bot");
        });
    }
    
    // إضافة الرسائل
    function addMessage(text, sender) {
        const messageDiv = document.createElement("div");
        messageDiv.className = sender + "-message";
        messageDiv.style.marginBottom = "10px";
        
        const isUser = sender === "user";
        messageDiv.innerHTML = `
            <div style="
                background: ${isUser ? "linear-gradient(135deg, #667eea 0%, #764ba2 100%)" : "#e9ecef"}; 
                color: ${isUser ? "white" : "#333"}; 
                padding: 10px; 
                border-radius: ${isUser ? "15px 15px 5px 15px" : "15px 15px 15px 5px"}; 
                max-width: 80%; 
                margin-left: ${isUser ? "auto" : "0"}; 
                margin-right: ${isUser ? "0" : "auto"}; 
                font-size: 14px;
                white-space: pre-line;
            ">
                ${text}
            </div>
        `;
        
        messages.appendChild(messageDiv);
        messages.scrollTop = messages.scrollHeight;
    }
    
    // إرسال عند الضغط على زر أو إنتر
    sendBtn.addEventListener("click", sendMessage);
    input.addEventListener("keypress", e => {
        if (e.key === "Enter") sendMessage();
    });
});
</script>
