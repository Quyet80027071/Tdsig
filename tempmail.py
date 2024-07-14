import requests
import time
import re
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
        code_pattern = re.compile(r'\b\w{4,}\b')  # Adjust the pattern based on your specific code format
        codes = code_pattern.findall(soup.get_text())
        return codes

    def fetch_and_print_emails(self):
        console.print(f"[bold yellow]Checking emails for:[/] {self.email}")
        time.sleep(10)  # Wait for a few seconds to allow receiving emails
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

# Usage
email_tool = EmailTool()

while True:
    console.print("\n[bold magenta]Options:[/]")
    console.print("[bold blue]1.[/] Change email")
    console.print("[bold blue]2.[/] Get code mail")
    console.print("[bold blue]3.[/] Display available custom domains")
    console.print("[bold blue]4.[/] Set custom email domain")
    console.print("[bold blue]5.[/] Exit")

    choice = Prompt.ask("[bold red]Enter your choice", choices=["1", "2", "3", "4", "5"], default="5")

    if choice == '1':
        email_tool.generate_random_email()
    elif choice == '2':
        email_tool.fetch_and_print_emails()
    elif choice == '3':
        email_tool.display_available_domains()
    elif choice == '4':
        custom_domain = Prompt.ask("[bold yellow]Enter custom domain")
        email_tool.generate_random_email(custom_domain)
    elif choice == '5':
        console.print(f"[bold green]Exiting the program.")
        break
    else:
        console.print(f"[bold red]Invalid choice. Please enter a number between 1 and 5.")