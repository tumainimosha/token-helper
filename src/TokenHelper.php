<?php

namespace Tumainimosha\TokenHelper;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Tumainimosha\TokenHelper\Exceptions\ExpiredTokenException;
use Tumainimosha\TokenHelper\Exceptions\InvalidTokenException;

class TokenHelper
{
    public const OK = 'ok';

    public const INVALID = 'invalid';

    public const EXPIRED = 'expired';

    /**
     * @param array $claims
     * @param int $expire_in_min
     * @return string
     */
    public function getToken(array $claims, int $expire_in_min = 60)
    {
        /** @var int $expires_at */
        $expires_at = Carbon::now()
            ->addMinutes($expire_in_min)
            ->timestamp;

        $tokenBuilder = $this->builder()->expiresAt($expires_at);

        // Add claims
        foreach ($claims as $key => $value) {
            $tokenBuilder->withClaim($key, $value);
        }

        // sign token and get as string
        $token = $tokenBuilder
            ->getToken($this->signer(), $this->signingKey())
            ->__toString();

        return $token;
    }

    /**
     * @param string $tokenString
     * @param string $claim
     * @return mixed
     * @throws ExpiredTokenException
     * @throws InvalidTokenException
     */
    public function getClaim(string $tokenString, string $claim)
    {
        if ($this::OK != $this->validateToken($tokenString)) {
            return null;
        }

        $token = $this->parseToken($tokenString);

        return $token->getClaim($claim);
    }

    /**
     * @param string $tokenString
     * @return array|null
     * @throws ExpiredTokenException
     * @throws InvalidTokenException
     */
    public function getClaims(string $tokenString)
    {
        if ($this::OK != $this->validateToken($tokenString)) {
            return null;
        }

        $token = $this->parseToken($tokenString);

        $allClaims = $token->getClaims();
        $allClaimsArr = [];
        foreach ($allClaims as $key => $value) {
            $allClaimsArr[] = [
                'key' => $key,
                'value' => $value->getValue(),
            ];
        }

        $claims = collect($allClaimsArr)
            ->pluck('value', 'key')
            ->toArray();

        return $claims;
    }

    /**
     * @param string $tokenString
     * @param array $claims
     * @return string
     */
    public function validateToken(string $tokenString, array $claims = [])
    {
        // Validate token
        try {
            $token = $this->parseToken($tokenString);
        } catch (ExpiredTokenException $e) {
            return $this::EXPIRED;
        } catch (InvalidTokenException $e) {
            Log::debug('Invalid token supplied');

            return $this::INVALID;
        } catch (\Exception $e) {
            Log::debug('Malformed token supplied');

            return $this::INVALID;
        }

        // Validate claims
        foreach ($claims as $key => $value) {
            $tokenValue = $token->getClaim($key);

            if (!$tokenValue != $value) {
                Log::debug("Invalid token: $key mismatch! $tokenValue != $value");

                return $this::INVALID;
            }
        }

        return $this::OK;
    }

    /**
     * @param string $tokenString
     * @return \Lcobucci\JWT\Token
     * @throws InvalidTokenException
     * @throws ExpiredTokenException
     */
    protected function parseToken(string $tokenString)
    {
        $token = $this->parser()->parse((string) $tokenString);

        // Check for expiration
        $validationData = new \Lcobucci\JWT\ValidationData();
        $isValid = $token->validate($validationData);

        if (!$isValid) {
            throw new ExpiredTokenException();
        }

        // Check for signature
        $isSigned = $token->verify($this->signer(), $this->signingKey());

        if (!$isSigned) {
            throw new InvalidTokenException();
        }

        return $token;
    }

    protected function signer()
    {
        return new \Lcobucci\JWT\Signer\Hmac\Sha256();
    }

    protected function signingKey()
    {
        $appKey = config('app.key');

        return new \Lcobucci\JWT\Signer\Key($appKey);
    }

    protected function parser()
    {
        return (new \Lcobucci\JWT\Parser());
    }

    protected function builder()
    {
        return (new \Lcobucci\JWT\Builder());
    }
}
