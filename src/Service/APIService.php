<?php

namespace CreamIO\BaseBundle\Service;

use CreamIO\BaseBundle\Exceptions\APIError;
use CreamIO\BaseBundle\Exceptions\APIException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Handles all logical operations related to the API activites.
 *
 * Operated mainly on serialization/deserialization, JSON and API response
 */
class APIService
{
    /**
     * Generates a JSONResponse for errored requests.
     *
     * This method is used for every methods since it handles all kind of errors
     *
     * @param int    $responseCode Response code to send (Constants defined in Response::class)
     * @param string $reason       Error reason to return
     *
     * @return APIException
     */
    public function error(int $responseCode, string $reason): APIException
    {
        $APIError = new APIError($responseCode, $reason);

        return new APIException($APIError);
    }

    public function postError(ConstraintViolationListInterface $validationErrors): APIException
    {
        $errors = [];
        foreach ($validationErrors as $error) {
            /* @var ConstraintViolation $error */
            $errors[$error->getPropertyPath()] = $error->getMessage();
        }

        $APIError = new APIError(Response::HTTP_BAD_REQUEST, APIError::VALIDATION_ERROR);
        $APIError->set('fields-validation-violations', $errors);

        return new APIException($APIError);
    }

    /**
     * Generates a JSONResponse for successful requests requiring results.
     *
     * This method is used for GET method since it required to return results
     *
     * @param mixed      $results    Datas to send as results, can be array of objects or a single object
     * @param int        $statusCode Response code to send (Constants defined in Response::class)
     * @param string     $resultsFor ID of the requested object, or identifier for collection requests
     * @param Request    $request    Handled HTTP request to get method from
     * @param Serializer $serializer Serializer to use (optional)
     *
     * @return JsonResponse
     */
    public function successWithResults($results, int $statusCode, string $resultsFor, Request $request, Serializer $serializer = null): JsonResponse
    {
        if (null === $serializer) {
            $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        }
        $return = [
            'status' => 'success',
            'code' => $statusCode,
            'request-method' => $request->getMethod(),
            'results-for' => $resultsFor,
            'results' => $results,
        ];
        $serializedReturn = $serializer->serialize($return, 'json');

        return new JsonResponse($serializedReturn, $statusCode, [], true);
    }

    /**
     * Generates a JSONResponse for successful requests not requiring any results.
     *
     * This method is used for DELETE, POST, PATCH and PUT methods since they do not need any result to return
     *
     * @param string     $id         Ressource ID
     * @param int        $statusCode Response code to send (Constants defined in Response::class)
     * @param Request    $request    Handled HTTP request to get method from
     * @param Serializer $serializer Serializer to use (optional)
     *
     * @return JsonResponse
     */
    public function successWithoutResults(string $id, int $statusCode, Request $request, Serializer $serializer = null): JsonResponse
    {
        if (null === $serializer) {
            $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        }
        $return = [
            'status' => 'success',
            'code' => $statusCode,
            'request-method' => $request->getMethod(),
            'request-ressource-id' => $id,
        ];
        $serializedReturn = $serializer->serialize($return, 'json');

        return new JsonResponse($serializedReturn, $statusCode, [], true);
    }

    /**
     * Generates a JSONResponse for successful requests not requiring any results but redirecting.
     *
     * This method is used to handle ressource creation requests since those requests generates a Location header redirecting to the created ressource get URL
     *
     * @param string     $id             Ressource ID
     * @param Request    $request        Handled HTTP request to get method from
     * @param int        $statusCode     Response code to send (Constants defined in Response::class)
     * @param string     $redirectionURL Location header URL
     * @param Serializer $serializer     Serializer to use (optional)
     *
     * @return JsonResponse Success response
     */
    public function successWithoutResultsRedirected(string $id, Request $request, int $statusCode, string $redirectionURL, Serializer $serializer = null): JsonResponse
    {
        if (null === $serializer) {
            $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        }
        $return = [
            'status' => 'success',
            'code' => $statusCode,
            'request-method' => $request->getMethod(),
            'request-ressource-id' => $id,
        ];
        $serializedReturn = $serializer->serialize($return, 'json');

        return new JsonResponse($serializedReturn, Response::HTTP_CREATED, ['Location' => $redirectionURL], true);
    }
}
