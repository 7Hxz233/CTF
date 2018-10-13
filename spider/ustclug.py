#!/usr/bin/env python
# encoding: utf-8

import json
import requests
import sys
import os
import tomd

website = 'https://hack.lug.ustc.edu.cn'

def parse_challenges(folder, challenges):
    for challenge in challenges:
        name = challenge['name']
        print("[+] Parsing %s" % (name))
        path = '%s/%s' % (folder, name)

        try:
            os.makedirs(path)
        except Exception as e:
            pass
        markdown = ''
        markdown += '#### %s  \n\n' % (name)
        markdown += '%s  \n' % (tomd.Tomd(challenge['detail']).markdown)
        markdown += '#### Hint:  \n\n'
        markdown += '``` \n'
        markdown += '%s  \n' % (challenge['prompt'])
        markdown += '``` \n'
        markdown += '#### Information:  \n\n'
        url = challenge['url']
        if url.startswith("/file/"):
            file_path = '%s/files' % (path)
            try:
                os.makedirs(file_path)
            except Exception as e:
                pass
            download_link = '%s%s' % (website, url)
            download(download_link, file_path)
            filename = url.split("/")[-1]
            markdown += '* File: [%s](files/%s)  \n\n' % (filename, filename)
        else:
            markdown += '* Url: %s  \n\n' % (challenge['url'])
        markdown += '* Soved: %d  \n\n' % (challenge['user_solved'])
        with open('%s/README.md' % path, 'w') as f:
            f.write(markdown)

def download(url, folder):
    print("[+] Download %s to %s" % (url, folder))
    filename = url.split("/")[-1]
    content = requests.get(url).content
    path = '%s/%s' % (folder, filename)
    with open(path, 'wb') as f:
        f.write(content)

def main():
    json_file = sys.argv[1]
    folder = sys.argv[2]
    challenges = json.load(open(json_file))
    parse_challenges(folder, challenges)

if __name__ == '__main__':
    main()
