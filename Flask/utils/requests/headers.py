def get_headers(key, cookie, token):
    return {
        "Accept": "application/json, text/plain, */*",
        "Content-Type": "application/json;charset=UTF-8",
        "User-Agent": "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36",
        "Origin": "https://example.com",
        "Referer": "https://example.com?kd_ky_key={}".format(key),
        "ec-Fetch-Mode": "cors",
        "Sec-Fetch-Site": "same-origin",
        "Cookie": cookie,
        "X-CSRF-TOKEN": token,
        "X-Requested-With": "XMLHttpRequest"
    }
