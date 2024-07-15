import time
from selenium import webdriver
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.chrome.options import Options
from bs4 import BeautifulSoup
from rich.console import Console
from rich.prompt import Prompt
from rich.table import Table

console = Console()

class EmailTool:
    def __init__(self):
        self.available_domains = self.fetch_available_domains()
        self.generate_random_email()

    def fetch_available_domains(self):
        try:
            url = "https://www.1secmail.com/api/v1/?action=getDomainList"
            response = requests.get(url)
            response.raise_for_status()
            domains = response.json()
            return domains
        except requests.RequestException as e:
            console.print(f"[bold red]Failed to fetch available domains:[/] {e}")
            return []

    def generate_random_email(self, custom_domain=None):
        try:
            url = "https://www.1secmail.com/api/v1/?action=genRandomMailbox&count=1"
            response = requests.get(url)
            response.raise_for_status()
            self.email = response.json()[0]
            self.login, self.domain = self.email.split('@')
            if custom_domain and custom_domain in self.available_domains:
                self.domain = custom_domain
                self.email = f"{self.login}@{self.domain}"
            console.print(f"[bold cyan]Generated email:[/] [bold yellow]{self.email}")
        except requests.RequestException as e:
            console.print(f"[bold red]Failed to generate email:[/] {e}")

    def get_messages(self):
        try:
            url = f"https://www.1secmail.com/api/v1/?action=getMessages&login={self.login}&domain={self.domain}"
            response = requests.get(url)
            response.raise_for_status()
            messages = response.json()
            return messages
        except requests.RequestException as e:
            console.print(f"[bold red]Failed to get messages:[/] {e}")
            return []

    def read_message(self, email_id):
        try:
            url = f"https://www.1secmail.com/api/v1/?action=readMessage&login={self.login}&domain={self.domain}&id={email_id}"
            response = requests.get(url)
            response.raise_for_status()
            message = response.json()
            return message
        except requests.RequestException as e:
            console.print(f"[bold red]Failed to read message:[/] {e}")
            return {}

    def extract_code_from_email(self, email_content):
        soup = BeautifulSoup(email_content, 'html.parser')
        code_pattern = re.compile(r'\b\w{4,}\b')  # Điều chỉnh mẫu dựa trên định dạng mã của bạn
        codes = code_pattern.findall(soup.get_text())
        return codes

    def fetch_and_print_emails(self):
        console.print(f"[bold yellow]Checking emails for:[/] {self.email}")
        time.sleep(10)  # Chờ một vài giây để nhận được email
        messages = self.get_messages()
        
        if messages:
            table = Table(title="Messages Received")
            table.add_column("ID", justify="center", style="cyan", no_wrap=True)
            table.add_column("From", justify="center", style="magenta")
            table.add_column("Subject", justify="center", style="green")

            for message in messages:
                table.add_row(str(message['id']), message['from'], message['subject'])
            
            console.print(table)
            
            for message in messages:
                email_id = message['id']
                email_content = self.read_message(email_id).get('body', '')
                if email_content:
                    codes = self.extract_code_from_email(email_content)
                    if codes:
                        console.print(f"[bold green]Extracted codes:[/] {codes}")
                    else:
                        console.print(f"[bold red]No code found in the email content.")
                else:
                    console.print(f"[bold red]Failed to read email content.")
        else:
            console.print(f"[bold red]No messages received.")

    def display_available_domains(self):
        if self.available_domains:
            table = Table(title="Available Domains")
            table.add_column("Domain", justify="center", style="cyan")
            for domain in self.available_domains:
                table.add_row(domain)
            console.print(table)
        else:
            console.print(f"[bold red]No available domains to display.")

    def register_facebook_from_email(self, password, first_name, last_name):
        try:
            chrome_driver_path = "chromedriver.exe.zip/chromedriver.exe"  # Đường dẫn đến Chrome WebDriver
            chrome_options = Options()
            chrome_options.add_argument("--headless")  # Chạy ẩn danh để không hiển thị trực tiếp trên màn hình
            service = Service(chrome_driver_path)
            driver = webdriver.Chrome(service=service, options=chrome_options)

            try:
                driver.get("https://m.facebook.com/reg/#")

                time.sleep(3)

                driver.find_element_by_name("firstname").send_keys(first_name)
                driver.find_element_by_name("lastname").send_keys(last_name)
                driver.find_element_by_name("reg_email__").send_keys(self.email)
                driver.find_element_by_name("reg_email_confirmation__").send_keys(self.email)
                driver.find_element_by_name("reg_passwd__").send_keys(password)

                driver.find_element_by_name("birthday_day").send_keys("1")
                driver.find_element_by_name("birthday_month").send_keys("Jan")
                driver.find_element_by_name("birthday_year").send_keys("1990")

                driver.find_element_by_name("sex").click()

                driver.find_element_by_name("websubmit").click()

                time.sleep(10)
            finally:
                driver.quit()
        except Exception as e:
            console.print(f"[bold red]Failed to register Facebook account:[/] {e}")

# Sử dụng class EmailToolExtended để đăng ký tài khoản Facebook từ email đã tạo
email_tool = EmailTool()
email_tool.generate_random_email()

password = "Quyet80027071:)"  # Thay "your_password" bằng mật khẩu bạn muốn sử dụng
first_name = "Nguyễn"  # Thay "John" bằng first_name bạn muốn sử dụng
last_name = "Linh"  # Thay "Doe" bằng last_name bạn muốn sử dụng

email_tool.register_facebook_from_email(password, first_name, last_name)
