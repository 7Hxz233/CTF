#!/usr/bin/env ruby
class String

  PRIME = 257

  class OutOfBoundError < ::RuntimeError; end
  class MaxRetryError < ::RuntimeError; end

  def ssss_split(available, needed)
    coef = Array.new(needed + 1) { rand(PRIME) }
    shares = Array.new(available + 1) { Array.new }

    self.each_byte.with_index do |byte, index|
      retry_time = 0
      begin
        1.upto(available) do |x|
          accu = byte
          1.upto(needed - 1) {|p| accu = (accu + coef[p] * x**p) % PRIME}
          if accu == 256 
            raise OutOfBoundError if retry_time < 20
            raise MaxRetryError
          end
          shares[x][index] = accu
        end
      rescue OutOfBoundError => e
        coef = Array.new(needed + 1) { rand(PRIME) }
        retry_time += 1
        retry
      rescue MaxRetryError => e
        shares.each {|s| s[index] = byte}
        next
      end
    end

    shares
  end

  def pp_split(available, needed)
    shares = ssss_split(available, needed)
    puts "Secret is #{self}"
    shares[1..-1].each_with_index do |arr, index|
      puts "#{index + 1}. #{arr.map {|byte| "%02x" % byte}}"
    end
  end

  def self.extended_gcd(a, b)
    last_remainder, remainder = a.abs, b.abs
    x, last_x, y, last_y = 0, 1, 1, 0
    while remainder != 0
      last_remainder, (quotient, remainder) = remainder, last_remainder.divmod(remainder)
      x, last_x = last_x - quotient*x, x
      y, last_y = last_y - quotient*y, y
    end
   
    return last_remainder, last_x * (a < 0 ? -1 : 1)
  end
   
  def self.invmod(e, et)
    g, x = extended_gcd(e, et)
    if g != 1
      raise 'Teh maths are broken!'
    end
    x % et
  end

  def self.ssss_single_byte_combine(byte_hash)
    accu = 0
    index = byte_hash.keys
    index.each do |current_x|
      denom = 1
      numer = 1
      index.each do |x|
        next if x == current_x
        denom *= x - current_x
        numer *= x
      end
      accu += (numer * byte_hash[current_x] * invmod(denom, PRIME))
    end
    accu % PRIME
  end

  def self.ssss_combine(hash)
  end
end

class Array
  def to_hex_string
    map {|e| "%02x" % e}.join
  end
end

flag = IO.read('flag')
shares = flag.ssss_split(5, 3).map{|s| s.to_hex_string}
INDEX = [1, 2, 3, 4, 5]

(flag.length - 1).downto(0) do |i|
  broken = INDEX.sample(rand(3))
  broken.each {|j| shares[j][i*2] = shares[j][i*2 + 1] = '*'}
end

1.upto(5) do |i|
  f = open("secret/#{i}.txt", 'w')
  f.write(shares[i])
  f.close
end

shares.each_with_index do |e, index|
  puts "#{index + 1}. #{e}"
end

secret = []
recover_flag = ""
1.upto(5) do |i|
  secret[i] = IO.read("secret/#{i}.txt").scan(/.{2}/)
end

0.upto(secret[1].length - 1) do |i|
  h = {}
  1.upto(5) do |j|
    if secret[j][i] != "**"
      h[j] = secret[j][i].to_i(16)
    end
  end
  recover_flag << String.ssss_single_byte_combine(h)
end

puts recover_flag
