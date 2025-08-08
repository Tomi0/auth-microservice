<?php

namespace App\UI\Http\Controllers\Authentication\AccessToken;

use App\UI\Http\Controllers\Controller;
use App\UI\Http\Validators\Authentication\AccessToken\GetAccessTokenValidator;
use Authentication\Application\Service\AccessToken\GetAccessToken;
use Authentication\Application\Service\AccessToken\GetAccessTokenRequest;
use Authentication\Domain\Model\AuthorizationCode\InvalidAuthorizationCodeException;
use Authentication\Domain\Model\Client\ClientNameAndSecretMissmatchException;
use Authentication\Domain\Model\Client\ClientNotFoundException;
use Authentication\Domain\Model\SigningKey\SigningKeyNotFoundException;
use Authentication\Domain\Model\User\UserNotFoundException;
use Illuminate\Http\JsonResponse;

class GetAccessTokenController extends Controller
{
    private GetAccessToken $getAccessToken;

    public function __construct(GetAccessToken $getAccessToken)
    {
        $this->getAccessToken = $getAccessToken;
    }

    /**
     * @throws UserNotFoundException
     * @throws ClientNameAndSecretMissmatchException
     * @throws ClientNotFoundException
     * @throws InvalidAuthorizationCodeException
     * @throws SigningKeyNotFoundException
     */
    public function __invoke(GetAccessTokenValidator $getAccessTokenValidator): JsonResponse
    {
        return response()->json($this->getAccessToken->handle(new GetAccessTokenRequest(
            $getAccessTokenValidator->input('clientSecret'),
            $getAccessTokenValidator->input('code'),
            $getAccessTokenValidator->input('clientName')
        )));
    }
}
