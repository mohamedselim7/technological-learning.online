<div id="chatbot-container" style="position: fixed; bottom: 20px; right: 20px; z-index: 9999;">
    <!-- زر فتح/إغلاق الشات بوت -->
    <div id="chatbot-toggle" style="
        width: 60px; 
        height: 60px; 
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
        border-radius: 50%; 
        cursor: pointer; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        transition: all 0.3s ease;
    ">
        <i class="bi bi-chat-dots-fill" style="color: white; font-size: 24px;"></i>
    </div>
    
    <!-- نافذة الشات -->
    <div id="chatbot-window" style="
        position: absolute; 
        bottom: 70px; 
        right: 0; 
        width: 400px; 
        height: 500px; 
        background: white; 
        border-radius: 15px; 
        box-shadow: 0 8px 25px rgba(0,0,0,0.2); 
        display: none;
        flex-direction: column;
        overflow: hidden;
    ">
        <!-- رأس الشات -->
        <div style="
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
            color: white; 
            padding: 15px; 
            text-align: center; 
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
        ">
            <div>
                <i class="bi bi-robot"></i> مساعد ذكي
            </div>
            <div style="display: flex; gap: 10px;">
                <button id="chat-mode-btn" style="background: rgba(255,255,255,0.2); border: none; color: white; padding: 5px 10px; border-radius: 5px; cursor: pointer; font-size: 12px;">
                    محادثة
                </button>
                <button id="faq-mode-btn" style="background: rgba(255,255,255,0.2); border: none; color: white; padding: 5px 10px; border-radius: 5px; cursor: pointer; font-size: 12px;">
                    أسئلة شائعة
                </button>
            </div>
        </div>
        
        <!-- وضع المحادثة -->
        <div id="chat-mode" style="flex: 1; display: flex; flex-direction: column;">
            <!-- منطقة الرسائل -->
            <div id="chatbot-messages" style="
                flex: 1; 
                padding: 15px; 
                overflow-y: auto; 
                background: #f8f9fa;
                max-height: 300px;
                scroll-behavior: smooth;
                scrollbar-width: thin;
                scrollbar-color: #ccc #f8f9fa;
            ">
                <div class="bot-message" style="margin-bottom: 10px;">
                    <div style="
                        background: #e9ecef; 
                        padding: 10px; 
                        border-radius: 15px 15px 15px 5px; 
                        max-width: 80%; 
                        font-size: 14px;
                    ">
                        مرحباً! أنا مساعدك الذكي. اسألني عن أي شيء متعلق بالثورة الصناعية الرابعة، الذكاء الاصطناعي، أو البيئة.
                    </div>
                </div>
            </div>
            
            <!-- منطقة التلميحات -->
            <div id="chatbot-suggestions" style="padding: 10px 15px; border-top: 1px solid #eee; background: #f0f0f0; display: flex; flex-wrap: wrap; gap: 5px; justify-content: center;">
                <!-- Suggestions will be loaded here -->
            </div>

            <!-- منطقة الإدخال -->
            <div style="padding: 15px; border-top: 1px solid #dee2e6;">
                <div style="display: flex; gap: 10px;">
                    <input 
                        id="chatbot-input" 
                        type="text" 
                        placeholder="اكتب سؤالك هنا..." 
                        style="
                            flex: 1; 
                            padding: 10px; 
                            border: 1px solid #dee2e6; 
                            border-radius: 20px; 
                            outline: none;
                            font-size: 14px;
                        "
                    >
                    <button 
                        id="chatbot-send" 
                        style="
                            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
                            color: white; 
                            border: none; 
                            border-radius: 50%; 
                            width: 40px; 
                            height: 40px; 
                            cursor: pointer;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                        "
                    >
                        <i class="bi bi-send-fill"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- وضع الأسئلة الشائعة -->
        <div id="faq-mode" style="flex: 1; display: none; flex-direction: column;">
            <div id="faq-content" style="
                flex: 1; 
                padding: 15px; 
                overflow-y: auto; 
                background: #f8f9fa;
            ">
                <div style="text-align: center; padding: 20px; color: #666;">
                    جاري تحميل الأسئلة الشائعة...
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const toggle = document.getElementById("chatbot-toggle");
    const window = document.getElementById("chatbot-window");
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
        if (window.style.display === "none" || window.style.display === "") {
            window.style.display = "flex";
            if (currentMode === 'chat') {
                input.focus();
            }
        } else {
            window.style.display = "none";
        }
    });
    
    // تبديل الأوضاع
    chatModeBtn.addEventListener("click", function() {
        switchMode('chat');
    });
    
    faqModeBtn.addEventListener("click", function() {
        switchMode('faq');
    });
    
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
        fetch('/chatbot/questions')
            .then(response => response.json())
            .then(questions => {
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
                
                // إضافة event listeners للأسئلة
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
                    
                    question.addEventListener('mouseenter', function() {
                        if (this.style.background !== 'rgb(233, 236, 239)') {
                            this.style.background = '#f8f9fa';
                        }
                    });
                    
                    question.addEventListener('mouseleave', function() {
                        if (this.style.background !== 'rgb(233, 236, 239)') {
                            this.style.background = '#fff';
                        }
                    });
                });
            })
            .catch(error => {
                console.error('Error loading FAQ:', error);
                faqContent.innerHTML = '<div style="text-align: center; padding: 20px; color: #666;">حدث خطأ في تحميل الأسئلة الشائعة</div>';
            });
    }
    
    // تحميل وعرض التلميحات
    fetch('/chatbot-suggestions')
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
        .catch(error => console.error('Error loading suggestions:', error));

    // إرسال الرسالة
    function sendMessage() {
        const message = input.value.trim();
        if (!message) return;
        
        // إضافة رسالة المستخدم
        addMessage(message, "user");
        input.value = "";
        
        // إرسال الطلب للخادم
        fetch("/chatbot", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector("meta[name=\"csrf-token\"]")?.getAttribute("content") || ""
            },
            body: JSON.stringify({message: message})
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
    
    // إضافة رسالة للشات
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
                word-wrap: break-word;
                white-space: pre-line;
            ">
                ${text}
            </div>
        `;
        
        messages.appendChild(messageDiv);
        messages.scrollTop = messages.scrollHeight;
    }
    
    // Event listeners
    sendBtn.addEventListener("click", sendMessage);
    input.addEventListener("keypress", function(e) {
        if (e.key === "Enter") {
            sendMessage();
        }
    });
});
</script>


<style>
/* تحسين شكل شريط التمرير في الشات بوت */
#chatbot-messages::-webkit-scrollbar {
    width: 6px;
}

#chatbot-messages::-webkit-scrollbar-track {
    background: #f8f9fa;
    border-radius: 3px;
}

#chatbot-messages::-webkit-scrollbar-thumb {
    background: #ccc;
    border-radius: 3px;
}

#chatbot-messages::-webkit-scrollbar-thumb:hover {
    background: #999;
}

#faq-content::-webkit-scrollbar {
    width: 6px;
}

#faq-content::-webkit-scrollbar-track {
    background: #f8f9fa;
    border-radius: 3px;
}

#faq-content::-webkit-scrollbar-thumb {
    background: #ccc;
    border-radius: 3px;
}

#faq-content::-webkit-scrollbar-thumb:hover {
    background: #999;
}

/* تحسين الرسوم المتحركة */
#chatbot-toggle:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 16px rgba(0,0,0,0.4);
}

.faq-question:hover {
    background: #f8f9fa !important;
}

/* تحسين شكل الرسائل */
.bot-message, .user-message {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

