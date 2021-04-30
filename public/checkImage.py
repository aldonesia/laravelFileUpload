#!/usr/bin/env python
import cv2
import os
import sys

if __name__=="__main__":
   
    nmFile=sys.argv[1]
    print(nmFile)
    dim=96
    img = cv2.imread(nmFile)
    if img is None:
        os.system('del %s'%nmFile)
        print("REJECTED, not image remove , no add to repository  ")
    else:
        print("ACCEPTED")

        
