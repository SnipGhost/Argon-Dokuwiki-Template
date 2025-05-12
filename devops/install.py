import requests

cookies = {"...": "..."}
sectok = "..."
package_url = (
    "https://github.com/SnipGhost/Argon-Dokuwiki-Template/archive/refs/heads/master.zip"
)

headers = {"Content-Type": "multipart/form-data;"}
params = {"do": "admin", "page": "extension", "tab": "install"}

files = {
    "sectok": (None, sectok),
    "installurl": (None, package_url),
    "installfile": ("", "", "application/octet-stream"),
    "overwrite": (None, "1"),
}

response = requests.post(
    "https://halgebra.math.msu.su/wiki-dev/doku.php/main",
    params=params,
    cookies=cookies,
    headers=headers,
    files=files,
)
