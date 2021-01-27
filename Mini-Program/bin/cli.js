#!/usr/bin/env node

const fs = require("fs");
const stat=fs.stat;
const { resolve } = require('path')

var copy = function(src,dst){
    fs.readdir(src, function(err,paths){
        if(err) throw err;
        paths.forEach(function(path){
            var _src=src+'/'+path;
            var _dst=dst+'/'+path;
            var readable = null;
            var writable = null;
            stat(_src, function(err,st){
                if(err) throw err;
                if(st.isFile()){
                    readable=fs.createReadStream(_src);//创建读取流
                    writable=fs.createWriteStream(_dst);//创建写入流
                    readable.pipe(writable);
                }else if(st.isDirectory()){
                    exists(_src,_dst,copy);
                }
            });
        });
    });
}

var exists = function(src,dst,callback){
    //测试某个路径下文件是否存在
    fs.exists(dst,function(exists){
        if(exists){//不存在
            callback(src,dst);
        }else{//存在
            fs.mkdir(dst,function(){//创建目录
                callback(src,dst)
            })
        }
    })
}

// console.log(__dirname, resolve('./'), process.cwd())

var arguments = process.argv.splice(2);
exists(__dirname + '/../package/', arguments[0] || 'mini-program', copy);