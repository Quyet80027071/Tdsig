import requests, telebot, datetime, os
from time import sleep
from threading import Thread
os.system("cls" if os.name == "nt" else "clear")
print(f'\tKhởi Chạy Bot! / {str(datetime.date.today())} - {datetime.datetime.now().strftime("%H:%M:%S")}')
data = {"botToken": "nhập token của bạn"}
TOKEN = data['botToken']
class Instagram:
    def __init__(self, cookie):
        self.headers = {
            'authority': 'www.instagram.com',
            'accept': '*/*',
            'Accept-Language': 'vi',
            'Content-Type': 'application/x-www-form-urlencoded',
            'Cookie': cookie,
            'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
            'X-Asbd-Id': '129477',
            'X-Csrftoken': cookie.split('csrftoken=')[1].split(';')[0],
            'X-Ig-App-Id': '936619743392459',
            'X-Ig-Www-Claim': 'hmac.AR0NRxumKZANcw6vbF0BcgCXzx8t8oo54mjvMdE1mAdtIPyt',
            'X-Instagram-Ajax': '1013101842',
            'X-Requested-With': 'XMLHttpRequest',
            'Sec-Ch-Prefers-Color-Scheme': 'light',
            'Sec-Ch-Ua': '"Chromium";v="124", "Google Chrome";v="124", "Not-A.Brand";v="99"',
            'Sec-Ch-Ua-Full-Version-List': '"Chromium";v="124.0.6367.63", "Google Chrome";v="124.0.6367.63", "Not-A.Brand";v="99.0.0.0"',
            'Sec-Ch-Ua-Mobile': '?0',
            'Sec-Ch-Ua-Platform': "Windows",
            'Sec-Ch-Ua-Platform-Version': '"15.0.0"',
            'Sec-Fetch-Dest': 'empty',
            'Sec-Fetch-Mode': 'cors',
            'Sec-Fetch-Site': 'same-origin',
        }
        self.id = cookie.split('ds_user_id=')[1].split(";")[0]
    def check_live(self):
        url = f"https://i.instagram.com/api/v1/users/{self.id}/info/"
        try:
            response = requests.get(url, headers=self.headers)
            response.raise_for_status()
            home = response.json()
            if "user" in home and "username" in home["user"]:
                user_name = home["user"]["username"]
                return {'user': user_name, 'id': self.id}
            else:
                return False
        except:
            return False
    def Like(self, link):
        try:
            post = requests.get(link, headers=self.headers).text
            id = post.split('{"media_id":"')[1].split('"')[0]
            like = requests.post("https://www.instagram.com/web/likes/"+id+"/like/", headers=self.headers).text
            sleep(5)
            if '{"status":"ok"}' in like:
                return True
            elif '"spam":true' in like:
                return "spam"
        except:
            return False
class TraoDoiSub:
    def __init__(self, token):
        self.token = token
    def information(self):
        try:
            info = requests.get(f'https://traodoisub.com/api/?fields=profile&access_token={self.token}').json()['data']
            user = info['user']
            xu = info['xu']
            xudie = info['xudie']
            return {'user': user, 'xu': xu, 'xudie': xudie}
        except:
            return False
bot = telebot.TeleBot(TOKEN)
class BotTelegram:
    def __init__(self):
        self.delay = 5
        self.stop = False
        self.dict_user = {}
        self.run_bot = False
        self.cookie = None
        self.token = None
        @bot.message_handler(commands=['start'])
        def start(message):
            thongtin = '''Welcome To DHP07 Bot Tds Instagram\n1 <> Để đăng kí chạy bot dùng lệnh /sign\n2 <> Xem hướng dẫn chạy bot dùng lệnh /huongdan\n3 <> Liên hệ admin Telegram: t.me/dh_phuoc\n4 <> Thông tin admin dùng lệnh /info_admin\n5 <> Bot TraoDoiSub Facebook: Update'''
            bot.send_message(message.chat.id, text=thongtin)
        @bot.message_handler(commands=['sign'])
        def sign(message):
            bot.reply_to(message, text='Đăng Kí Thành Công!')
        @bot.message_handler(commands=['info_admin'])
        def info(message):
            info = '''Thông Tin Cơ Bản\nName: Đàm Hữu Phước\nNick Name: DHP07\nDate Of Birth: 21/03/2007\nHobby: 🎧🎮🎸💻\nFavorite Food: 🍫🍩🍮🍇\n\nThông Tin Liên Hệ\nTelegram: t.me/dh_phuoc\nZalo: zalo.me/0862964954\nFacebook: facebook.com/dhphuoc.207'''
            bot.send_message(message.chat.id, text=info)
        @bot.message_handler(commands=['huongdan'])
        def huongdan(message):
            huongdan = '''Cách Chạy Bot Instagram\nB1 <> Dùng lệnh /cookie {cookie_ig}\nB2 <> Dùng lệnh /token {token_tds}\nB3 <> Dùng lệnh /delay {delay}\nB4 <> Dùng lệnh /run Để Bắt Đầu Làm Nhiệm Vụ\nĐể tạm dừng tương tác khi bot đang chạy dùng lệnh /stop\nĐể tiếp tục chạy sau khi dùng /stop dùng lệnh /restart'''
            bot.send_message(message.chat.id, text=huongdan)
        @bot.message_handler(commands=['stop'])
        def stop(message):
            if self.stop == False:
                self.stop = True
                bot.reply_to(message, text='Đã Tạm Dừng Tương Tác, Để Tiếp Tục Chạy Sử Dụng Lệnh /restart')
            else:
                bot.reply_to(message, text='Bot Đang Dừng, Để Tiếp Tục Chạy Sử Dụng Lệnh /restart')
        @bot.message_handler(commands=['restart'])
        def restart(message):
            if self.stop == True:
                self.stop = False
                bot.reply_to(message, text='Bắt Đầu Chạy Tương Tác!')
            else:
                bot.reply_to(message, text='Bot Đang Chạy Tương Tác!')
        @bot.message_handler(commands=['delay'])
        def delay(message):
            try:
                self.delay = int(message.text.split(' ', 1)[1])
                bot.send_message(message.chat.id, f'Đã Add Delay {self.delay} Giây')
            except:
                self.delay = 5
                bot.send_message(message.chat.id, 'Delay Phải Là Số Nguyên Tố!')
        @bot.message_handler(commands=['cookie'])
        def add_cookie(message):
            try:
                cookie = message.text.split(' ', 1)[1]
                ig = Instagram(cookie)
                check = ig.check_live()
                
                if not check:
                    bot.send_message(message.chat.id, text='Cookie Die Or Văng')
                else:
                    user = check['user']
                    uid = check['id']
                    bot.send_message(message.chat.id, text=f'Uid Ig: {uid} <> User Ig: {user}')
                    self.cookie = cookie
                    self.dict_user['cookie'] = cookie
            except:
                pass
        @bot.message_handler(commands=['token'])
        def add_token(message):
            try:
                token = message.text.split(' ', 1)[1]
                tds = TraoDoiSub(token).information()
                if tds is False:
                    bot.send_message(message.chat.id, text='Token Không Hợp Lệ!')
                else:
                    bot.send_message(message.chat.id, text=f'User: {tds["user"]} <> Coin: {tds["xu"]}') 
                    self.token = token
                    self.dict_user['token'] = token
            except:
                pass
        @bot.message_handler(commands=['run'])
        def run(message):
            if not self.cookie or not self.token:
                bot.send_message(message.chat.id, text='Vui Lòng Thêm Token Tds Và Cookie Instagram Trước Đã')
            else:
                token = self.token
                tds = TraoDoiSub(token).information()
                if tds is False:
                    bot.send_message(message.chat.id, text='Token Không Hợp Lệ!')
                else:
                    text = f'''Bắt Đầu Chạy Cho\nUser: {tds["user"]} <> Coin: {tds["xu"]}'''
                    bot.reply_to(message, text=text)
            def instagram_run():
                try:
                    ig = Instagram(self.cookie)
                    check = ig.check_live()
                    if check is False:
                        bot.reply_to(message, text='Cookie Die Or Văng')
                        return
                    uid = check['id']
                    user = check['user']
                    cauhinh = requests.get(f'https://traodoisub.com/api/?fields=instagram_run&id={uid}&access_token={self.token}').json()
                    if not 'success' in cauhinh:
                        bot.reply_to(message, text=f'Cấu Hình {user} Thất Bại, {user} Chưa Được Thêm Vào Cấu Hình')
                        return
                    msg = cauhinh["data"]["msg"]
                    bot.send_message(message.chat.id, text=f'{msg}, User: {user}')
                except:
                    pass
            def like_run():
                try:
                    list_job = requests.get(f'https://traodoisub.com/api/?fields=instagram_like&access_token={self.token}').json()['data']
                    if 'error' in list_job:
                        bot.send_message(message.chat.id, text='Lỗi Không Get Được Nhiệm Vụ')
                    if list_job == []:
                        bot.send_message(message.chat.id, text='Tạm Thời Hết Nhiệm Vụ')
                    sleep(3)
                    for job in list_job:
                        idjob = job['id']
                        link = job['link']
                        Instagram.Like(link)
                        duyet = requests.get(f'https://traodoisub.com/api/coin/?type=INS_LIKE&id={idjob}&access_token={self.token}').json()['data']
                        pending = duyet['xu']
                        msg = duyet['msg']
                        des = f'''Làm Thành Công Nhiệm Vụ Like\nUrl: {link.split('https://www.')[1]}\nXu Cộng: {msg}\nTổng Có {pending} Xu'''
                        bot.send_message(message.chat.id, text=des)
                        sleep(5)
                except:
                    pass
            def chay():
                while True:
                    if self.stop == False:
                        like_run()
                        sleep(self.delay)
                    else:
                        pass
            Thread(target=instagram_run).start()
            Thread(target=chay).start()
        bot.polling()
BotTelegram()