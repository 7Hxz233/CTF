#!/usr/bin/env ruby
require 'openssl'
require 'base64'
require 'securerandom'

flag = IO.read("flag")
key = SecureRandom.random_bytes(16)
iv = SecureRandom.random_bytes(16)
IV = iv

$times = 0

$c = OpenSSL::Cipher::AES.new(128, :CBC)
$c.encrypt
$c.key = key
$c.iv  = iv

cipher = $c.update(flag) + $c.final
cipher = Base64.encode64(cipher).gsub("\n", "")

$d = OpenSSL::Cipher::AES.new(128, :CBC)
$d.decrypt
$d.key = key
$d.iv  = iv

STDOUT.flush

puts "I am the Oracle of puddings."
puts "I made the best pudding than ever:"
puts cipher
puts "Fool and humble human, i may grant thee ask me anything except my masterpieces."

def round(challenge, cipher)
  if challenge == cipher
    puts "You shall not pass!"
    exit
  elsif challenge == "exit"
    exit
  else
    begin
      bin = Base64.decode64(challenge)
      $d.iv = IV
      plain = $d.update(bin) + $d.final
      puts "plain is #{plain}."
      Base64.encode64(plain).gsub("\n", "")
    rescue ArgumentError => e
      "Error! You should using Base64 when talk to an oracle!"
    rescue => e
      "Error!" + e.message
      #"Error! Silly question!"
    end
  end
end

def exploit(cipher)

  bin = Base64.decode64(cipher)
  b1, b2, b3, b4 = *[bin[0..15], bin[16..31], bin[32..47], bin[48..63]]
  challenge1 = Base64.encode64(["0", b1[1..-1], b2, b3, b4].join).gsub("\n", "")
  challenge2 = Base64.encode64([b1, b2, "0", b3[1..-1], b4].join).gsub("\n", "")
  ret1 = round(challenge1, cipher)
  ret2 = round(challenge2, cipher)
  puts Base64.decode64(ret2)[0..31] + Base64.decode64(ret1)[32..-1]
  
end

exploit(cipher)

# loop do
#   puts "Ask me!"
#   challenge = gets.chomp
#   puts round(challenge, cipher)
# end

