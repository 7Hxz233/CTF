#!/usr/bin/env python
# encoding:utf-8

from bs4 import BeautifulSoup
import requests
import json
import sys
import os

class Spider():
    def __init__(self, url, username, password):
        self.url = url
        self.login_url = url + "/login"
        self.challenges_url = url + "/chals"
        self.username = username
        self.password = password
        self.session = requests.Session()

    def login_page(self):
        return self.session.get(self.login_url).content

    def csrf(self, content):
        return BeautifulSoup(content, "html.parser").find_all("input")[-1]['value']

    def login(self):
        nonce = self.csrf(self.login_page())
        data = {
            "name": self.username,
            "password": self.password,
            "nonce": nonce,
        }
        content = self.session.post(self.login_url, data=data).content
        if "Your username or password is incorrect" in str(content):
            return False
        return True

    def get_challenges(self):
        content = self.session.get(self.challenges_url).content
        return json.loads(content)

    def download(self, url, path):
        print("[+] Downloading from %s to %s" % (url, path))
        with open(path, "wb") as f:
            f.write(self.session.get(url).content)

    def get_detail(self, id):
        return json.loads(self.session.get(self.challenges_url + "/%d" % (id)).content)

    def handle_challenge(self, detail, target):
        name = detail['name']
        category = detail['category']
        description = detail['description']
        files = detail['files']
        if 'hints' in detail.keys():
            hints = detail['hints']
        else:
            hints = []
        # Make bucket to store
        path = "%s/%s/%s" % (target, category.lower(), name.replace(" ", "-"))
        try:
            os.makedirs(path)
        except Exception as e:
            pass
        # Generate markdown
        markdown = ""
        markdown += "#### [%s] %s  \n\n" % (category, name)
        markdown += "#### Description:   \n\n"
        markdown += "```\n%s\n```\n\n" % (description)
        if len(files) > 0:
            files_path = "%s/%s/%s/files" % (target, category.lower(), name.replace(" ", "-"))
            try:
                os.makedirs(files_path)
            except Exception as e:
                pass
            open("%s/.gitkeep" % (files_path), "a").close()
            markdown += "#### Files:   \n\n"
            for f in files:
                url = "%s/files/%s" % (self.url, f)
                markdown += "* %s  \n" % (url)
                # Download file
                self.download(url, "%s/%s" % (files_path, f.split("/")[-1]))
        if len(hints) > 0:
            markdown += "#### Hints:   \n\n"
            for h in hints:
                markdown += "* %s  \n" % (h)
        with open("%s/README.md" % (path), "w") as f:
            f.write(markdown.encode("utf-8"))

def main():
    if len(sys.argv) != 5:
        print("Usage: ")
        print("\tpython %s [URL] [USERNAME] [PASSWORD] [TARGET]" % (sys.argv[0]))
        exit(1)
    website = sys.argv[1]
    username = sys.argv[2]
    password = sys.argv[3]
    target = sys.argv[4]
    spider = Spider(website, username, password)
    print("[+] Fetching challenges from %s" % (website))
    if not spider.login():
        print("[-] Login failed!")
        return
    print("[+] Login succeed!")
    challenges = spider.get_challenges()["game"]
    print("[+] %d challenges found!" % (len(challenges)))
    for challenge in challenges:
        print("[+] Handling %s of %s" % (challenge['name'], challenge['category']))
        # Get data
        if 'files' in challenge.keys():
            # Old version
            spider.handle_challenge(challenge, target)
        else:
            # New version
            detail = spider.get_detail(challenge['id'])
            spider.handle_challenge(detail, target)

if __name__ == '__main__':
    main()
