#!/usr/bin/env ruby
require 'openssl'
require 'base64'
require 'securerandom'

FLAG = IO.read("flag")
KEY = SecureRandom.random_bytes(16)
IV = SecureRandom.random_bytes(16)

Encrypt = OpenSSL::Cipher::AES.new(128, :CBC)
Encrypt.encrypt
Encrypt.key = KEY
Encrypt.iv  = IV

cipher = Encrypt.update(FLAG) + Encrypt.final
cipher = Base64.encode64(cipher).gsub("\n", "")

Oracle = OpenSSL::Cipher::AES.new(128, :CBC)
Oracle.decrypt
Oracle.key = KEY
Oracle.iv = IV

STDOUT.flush

puts "I am the Oracle of puddings."
puts "I made the best pudding than ever:"
puts cipher
puts "Fool and humble human, i may grant thee query me anything except my masterpieces."

def round(challenge, cipher)
  if challenge == cipher
    puts "You shall not pass!"
    exit
  elsif challenge == "exit"
    exit
  else
    begin
      Oracle.iv = IV
      bin = Base64.decode64(challenge)
      plain = Oracle.update(bin) + Oracle.final
      Base64.encode64(plain).gsub("\n", "")
    rescue ArgumentError => e
      "Error! You should using Base64 when talk to an oracle!"
    rescue => e
      "Error! Silly question!"
    end
  end
end

times = 0
MAX_TIMES = 3

loop do
  puts "Ask me!"
  challenge = gets.chomp
  puts round(challenge, cipher)
  times += 1
  if times > MAX_TIMES
    puts "I'm angry"
    exit
  end
end

