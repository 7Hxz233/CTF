#!/usr/bin/env ruby
require 'openssl'
require 'base64'
require 'securerandom'

flag = IO.read("flag")
key = SecureRandom.random_bytes(16)
iv = SecureRandom.random_bytes(16)

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
      plain = $d.update(bin) + $d.final
      #puts plain   
      #Base64.encode64(plain).gsub("\n", "")
      "It's a good pudding."
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
  b1, b2, b3 = *[bin[0..15], bin[16..31], bin[32..47]]
  # challenge = Base64.encode64([b1, b2[1..-1], b3].join).gsub("\n", "")
  # puts challenge
  # ret = round(challenge, cipher)
  # puts ret
  i3 = Array.new(16)
  p3 = Array.new(16)
  i2 = Array.new(16)
  p2 = Array.new(16)


  # 后缀长度分别为 1、2、3……
  0.upto(15) do |i|
    0.upto(255) do |j|
      $times += 1
      cf = i3.last(i).map {|e| e ^ (i+1)}.map(&:chr)
      c = SecureRandom.random_bytes(15 - i) + j.chr + cf.join
      # puts "-" * 10
      # puts c.inspect
      # puts "-" * 10
      challenge = Base64.encode64([b1, c, b3].join).gsub("\n", "")
      ret = round(challenge, cipher)
      unless ret.include? "Error!"
        i3[15 - i] = (i + 1) ^ j
        p3[15 - i] = (b2[15 - i]).ord ^ i3[15 - i]
        # puts "=" * 10
        # puts Base64.decode64(ret).inspect
        # puts "i3 = #{i3[15 - i]}, p3 = #{p3[15 - i]}, j = #{j}"
        # puts "=" * 10
        break
      else
        # puts ret
      end
      # if ret.include? "bad decrypt"
      #   puts "i3[#{15 - i}] = #{(i + 1) ^ j}"
      #   puts "p3[#{15 - i}] = #{(b2[15 - i]).ord ^ (i + 1) ^ j}"
      # end
      if j == 255
        puts "missing!"
        exit
      end
    end

  end

  0.upto(15) do |i|
    0.upto(255) do |j|
      $times += 1
      cf = i2.last(i).map {|e| e ^ (i+1)}.map(&:chr)
      c = SecureRandom.random_bytes(15 - i) + j.chr + cf.join
      # puts "-" * 10
      # puts c.inspect
      # puts "-" * 10
      challenge = Base64.encode64([c, b2].join).gsub("\n", "")
      ret = round(challenge, cipher)
      unless ret.include? "Error!"
        i2[15 - i] = (i + 1) ^ j
        p2[15 - i] = (b1[15 - i]).ord ^ i2[15 - i]
        # puts "=" * 10
        # puts Base64.decode64(ret).inspect
        # # puts "i2 = #{i2[15 - i]}, p2 = #{p3[15 - i]}, j = #{j}"
        # puts "=" * 10
        break
      else
        # puts ret
      end
      # if ret.include? "bad decrypt"
      #   puts "i3[#{15 - i}] = #{(i + 1) ^ j}"
      #   puts "p3[#{15 - i}] = #{(b2[15 - i]).ord ^ (i + 1) ^ j}"
      # end
      if j == 255
        puts "missing!"
        exit
      end
    end

  end
  puts p2.map(&:chr).join
  puts p3.map(&:chr).join
  puts "-" * 20
  puts "total #{$times} times"
end

exploit(cipher)

# loop do
#   puts "Ask me!"
#   challenge = gets.chomp
#   puts round(challenge, cipher)
# end

