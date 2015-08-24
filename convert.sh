!# /bin/sh

SOURCE=$1
DESTINY=$2
FORMATS="240 360 480 720 1080"


convert(){
	/usr/local/Cellar/ffmpeg/2.3/bin/ffmpeg -y -i $SOURCE $1 $DESTINY/$2p.mp4 &
}

#convert "-s 426x240" 240
#convert "-s 640x360" 360
#convert "-s 854x480" 480
convert "-s 1280x720" 720
#convert "-s 1920x1080" 1080