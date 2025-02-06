import boto3
import os
import requests
import mimetypes

def get_s3() :
    if not hasattr(get_s3, 'cache'):
        print(os.getenv('AWS_KEY'))
        get_s3.cache = boto3.client('s3',
            aws_access_key_id = os.getenv('AWS_KEY'),
            aws_secret_access_key = os.getenv('AWS_SECRET'),
            region_name = 'us-east-1'
        )
    return get_s3.cache

def publish_file(local_file, remote_name):
    mime,_ = mimetypes.guess_type(local_file)
    print("Publishing {} as {} (mimetype = {})".format(local_file, remote_name,mime))
    return get_s3().upload_file(
        local_file,
        os.getenv('S3BUCKET'),
        remote_name,
        ExtraArgs={'ACL':'public-read','ContentType': mime}
    )

def is_published(remote_name):
    try:
        get_s3().head_object(
            Bucket=os.getenv('S3BUCKET'),
            Key=remote_name
        )
        return True
    except:
        return False

def file_url(name):
    return "https://"+os.getenv('S3BUCKET')+".s3.amazonaws.com/{}".format(name)
