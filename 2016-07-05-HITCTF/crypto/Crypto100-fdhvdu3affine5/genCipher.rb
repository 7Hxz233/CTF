#!/usr/bin/env ruby

def affine(str, a, b)
  str.each_char.map do |c|
    base = 'a'.ord
    (base + ((c.ord - base) * a + b) % 26).chr
  end.join
end

def caesar(str)
  affine(str, 1, 3)
end

flag = IO.read("flag")

CAESAR = (('a'..'z').zip ('a'..'z').map {|e| caesar(e)}).to_h
AFFINE = (('a'..'z').zip ('a'..'z').map {|e| affine(e, 3, 5)}).to_h

puts str = affine(caesar(flag), 3, 5)

puts str.each_char.map {|c| CAESAR.invert[AFFINE.invert[c]]}.join
