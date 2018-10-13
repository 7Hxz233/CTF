#!/usr/bin/env python
# encoding: utf-8

import json
import requests
import sys
import os

website = 'http://yxs.huwangbei.cn'

def parse_challenges(folder, challenges):
    for challenge in challenges:
        name = challenge['task']['title']
        category = challenge['task']['category_name']
        detail = challenge['task']['content']
        if challenge['task']['file_url']:
            challenge_url = challenge['task']['file_url']['url']
        else:
            challenge_url = None
        sovled_count = challenge['solved_count']
        if 'top_solved' in challenge.keys():
            top_soved = challenge['top_solved']
        else:
            top_soved = []
        dynamic_score = challenge['dynamic_score']
        print("[+] Parsing %s" % (name))
        path = '%s/%s/%s' % (folder, category, name)
        try:
            os.makedirs(path)
        except Exception as e:
            pass
        markdown = ''
        markdown += '## %s  \n\n' % (name)
        if detail:
            markdown += '%s  \n' % (detail)
        markdown += '#### Information:  \n\n'
        if challenge_url:
            file_path = '%s/files' % (path)
            try:
                os.makedirs(file_path)
            except Exception as e:
                pass
            download_link = '%s%s' % (website, challenge_url)
            download(download_link, file_path)
            filename = challenge_url.split("/")[-1]
            markdown += '* File: [%s](files/%s)  \n\n' % (filename, filename)
        markdown += '* Score: %d  \n\n' % (dynamic_score)
        markdown += '* Soved: %d  \n\n' % (sovled_count)
        for team in top_soved:
            markdown += '  * %s  \n\n' % (team['repr_name'])
        with open('%s/README.md' % path, 'w', encoding='utf-8') as f:
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
    challenges = json.load(open(json_file, encoding='utf-8'))['rows']
    parse_challenges(folder, challenges)

if __name__ == '__main__':
    main()