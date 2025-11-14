@if(Auth::user() && Auth::user()->name === 'gista')
    <div id="diaryOverlay" class="diary-overlay">
        <div class="diary-container">
            <!-- Header dengan Close Button -->
            <div class="diary-header">
                <button id="closeDiaryBtn" class="diary-close-btn" onclick="closeDiary()" title="Tutup">×</button>
            </div>

            <!-- Diary Content -->
            <div class="diary-content">
                <p id="diaryText" class="diary-text">
                    hai cantik apakabar maaf ya aku ga ada kabar maaf juga sama masalah masalah kemarin aku bikin gini karna gatau kenapa tiba tiba kepikiran pengen bikin kaya gini kangen kali ya haha gimana pkl terakhir kamu? rame? cape kah? aku juga udah lama ga denger kabar kamu lagi yaa ga enak banget harus kayak gini sekarang sama kita aku ngerti sama kamu cape kalo terus terusan sama aku yang ga pernah bisa ngasih apa yang kamu pengen mungkin nanti ada waktunya kita bisa bareng lagi maaf ya aku banyak bikin kesalahan sama kamu banyak minta ini itu banyak janji ini itu ke kamu maaf sama semuanya aku pasti terus ngejar kamu ada waktunya pasti kita bisa bareng lagi oh iya kalo nanti kamu baca ini waktu kita bareng lagi aku cuman pengen bilang makasih makasih sama apa yang udah kamu usahain makasih sama apa yang udah kamu kasih makasih atas semuanyaa aku beruntung pisan dapet kamu aku beruntung pisan kenal sama kamu makasih udah mau sama aku lagi kabarin ke rasya ya kalo kamu baca ini waktu kita bareng lagi haha kasih tau ke rasya jangan nyakitin wae gista gitu hahaa semoga ada waktunya kita bisa bareng lagi ya 🙂‍↕️ kalo misal kamu baca ini kita masih ga bareng yaaa aku minta maaf kaya yang aku ketik di atas di awal maksudnya jangan segan segan buat nanya ke aku kalo ada apa apa biasa aja aku juga pasti ngerti kok hehe semangat yah buat kamu cantik semangatttt semangat sayangg semangattt cantikkkkk semangattt kamu kalo mau hapus bagian ini boleh biar ga muncul terus waktu kamu login pake nama kamu (bolehdihapuskaloudahbaca) nanti hapus aja file file itu atau ga nanya ke aku juga boleh atau ke siapapun juga makasih dan maaf ya gabisa ada buat kamu muluuu haha dadahhh makasih oh iya maaf ya aku bikin kaya gini di web nya hehe dadahh
                </p>
            </div>
        </div>
    </div>

    <style>
        .diary-overlay {
            display: flex;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 9999;
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .diary-container {
            width: 90%;
            max-width: 600px;
            height: 80vh;
            background: linear-gradient(135deg, #f5e6d3 0%, #e8d4b8 100%);
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.3);
            display: flex;
            flex-direction: column;
            position: relative;
            border: 3px solid #d4a574;
            overflow: hidden;
            animation: slideUp 0.4s ease-out;
        }

        @keyframes slideUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .diary-header {
            padding: 16px 20px;
            background: linear-gradient(135deg, #8b6f47 0%, #6b5638 100%);
            border-bottom: 2px solid #5a4a2e;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .diary-close-btn {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.5);
            color: white;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            font-size: 24px;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: all 0.3s ease;
            font-weight: bold;
            padding: 0;
        }

        .diary-close-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            border-color: white;
            transform: scale(1.1);
        }

        .diary-content {
            flex: 1;
            padding: 24px;
            overflow-y: auto;
            background: linear-gradient(to bottom, #f5e6d3 0%, #faf5f0 100%);
        }

        .diary-content::-webkit-scrollbar {
            width: 8px;
        }

        .diary-content::-webkit-scrollbar-track {
            background: rgba(139, 111, 71, 0.1);
            border-radius: 10px;
        }

        .diary-content::-webkit-scrollbar-thumb {
            background: rgba(139, 111, 71, 0.3);
            border-radius: 10px;
        }

        .diary-content::-webkit-scrollbar-thumb:hover {
            background: rgba(139, 111, 71, 0.5);
        }

        .diary-text {
            font-family: 'Comic Sans MS', cursive, sans-serif;
            font-size: 15px;
            line-height: 1.8;
            color: #3d2817;
            margin: 0;
            white-space: pre-wrap;
            word-wrap: break-word;
            letter-spacing: 0.3px;
            text-shadow: 1px 1px 1px rgba(255, 255, 255, 0.5);
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
                transform: scale(1);
            }
            to {
                opacity: 0;
                transform: scale(0.95);
            }
        }
    </style>

    <script>
        function closeDiary() {
            const overlay = document.getElementById('diaryOverlay');
            if (overlay) {
                overlay.style.animation = 'fadeOut 0.3s ease-out';
                setTimeout(() => {
                    overlay.remove();
                }, 300);
            }
        }

        // Auto-open diary saat dashboard load
        document.addEventListener('DOMContentLoaded', function() {
            const overlay = document.getElementById('diaryOverlay');
            if (overlay) {
                overlay.style.display = 'flex';
            }
        });
    </script>
@endif
