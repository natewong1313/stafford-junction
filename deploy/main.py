import paramiko
import os
import mysql.connector
import io

ssh_client = paramiko.SSHClient()

PRIVATE_KEY = os.getenv("PRIVATE_KEY")
if PRIVATE_KEY == "":
    raise Exception("Private key env var not set")
PASSWORD = "&^23h#)&e52)"
SG_PATH = "www/jenniferp129.sg-host.com/public_html"

def run_new_sql_files():
    db = mysql.connector.connect(
        host="jenniferp129.sg-host.com",
        database="dbvswbwbmfnmrx",
        user="u8sj1xg2scpnb",
        password="362z7x6hkngw"
    )
    db_cur = db.cursor()

    new_sql_files = [f for f in os.getenv("ALL_CHANGED_FILES").split(" ") if f.endswith(".sql")]
    # open all the new sql files from the commit and run them
    for sql_file in new_sql_files:
        with open(sql_file) as f:
            sql_contents = f.read()
            try:
                db_cur.execute(sql_contents, multi = True)
            except Exception as e:
                print("Error occured running sql file", sql_file, ":", e)

    db.commit()
    db_cur.close()
    db.close()

# gh actions runs from parent directory, but when testing we'll run in the deploy directory
def build_path(dir):
    final_path = ""
    if os.getcwd().endswith("/deploy"):
        final_path = os.path.join(dir)
    else:
        final_path = os.path.join("./deploy", dir)
    return os.path.abspath(final_path)

# connect thru ssh
def do_connection():
    k = paramiko.RSAKey.from_private_key(io.StringIO(PRIVATE_KEY), password=PASSWORD)
    ssh_client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    ssh_client.connect("ssh.jenniferp129.sg-host.com", username="u1822-9mv4mev8he2u", password=PASSWORD, pkey=k, port=18765)

def do_upload():
    # create sftp so we can upload files
    sftp_client = ssh_client.open_sftp()
    parent_path = build_path("../")
    # iterate through all directories and subfiles in the root path
    for subdir, _, files in os.walk(parent_path):
        if should_ignore_upload(subdir):
            continue
        # create folder on siteground
        sg_dir = SG_PATH + subdir.split(parent_path)[1]
        if sg_dir != "":
            try:
                sftp_client.mkdir(sg_dir)
                print("Created", sg_dir, "on siteground")
            except IOError:
                # if folder exists itll throw this error
                pass
        # upload files
        for file in files:
            local_path = subdir + os.sep + file
            sg_path = SG_PATH + local_path.split(parent_path)[1]
            sftp_client.put(local_path, sg_path)
            print("Uploaded", file, "on siteground")
    sftp_client.close()

# checks if a directory contains folders we want to ignore
def should_ignore_upload(dir):
    ignore_folders = {"/deploy", "/.idea", "/.github", "/sql", "/.git"}
    for ignore_folder in ignore_folders:
        if ignore_folder in dir:
            return True
    return False


if __name__ == "__main__":
    try:
        run_new_sql_files()
    except:
        print("Sql failed")
    do_connection()
    do_upload()
    ssh_client.close()
