#!/bin/bash
for file in ./public/games/11/*CQ9
do
	mv ./public/games/${file##*/} ./public/games/${file##*/}_
	mv ${file} ./public/games/${file##*/}
done

