<?php

namespace App\Http\Handlers;
use Illuminate\Http\Request;
use Spatie\WebhookClient\WebhookConfig;
use Spatie\WebhookClient\Exceptions\InvalidConfig;
use Spatie\WebhookClient\SignatureValidator\SignatureValidator;

class MySignatureValidator implements SignatureValidator
{
    public function isValid(Request $request, WebhookConfig $config): bool
    {
        // TODO run thru a webhook profile first namespace Spatie\WebhookClient\WebhookProfile;?
        // TODO better validation  the content length?
        return true;
        $signature = $request->header($config->signatureHeaderName);
        // logger('Sig: '. $signature);
        if (! $signature) {
            return false;
        }

        $signingSecret = $config->signingSecret;

        if (empty($signingSecret)) {
            throw InvalidConfig::signingSecretNotSet();
        }

        $computedSignature = hash_hmac('sha256', $request->getContent(), $signingSecret);

        // logger($signature);
        // logger($computedSignature);

        return true;
        return hash_equals($computedSignature, $signature);
    }
}
