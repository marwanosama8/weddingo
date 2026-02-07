<?php

namespace App\Http\Custome;

/**
 * Class ApiResponseTrait
 *
 *
 */
trait Response
{

    /**
     * Resource was successfully created
     *
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createdResponse($data)
    {
        $response = $this->successEnvelope(201, $data, 'Created');

        return response()->json($response, 201);
    }

    /**
     * Resource for login
     *
     * @param $data
     * @param $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function logindResponse($data, $token)
    {
        $response = $this->successEnvelope(201, $data, $token);

        return response()->json($response, 201);
    }

    /**
     * Resource was successfully deleted
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function deletedResponse(
        $status = 200,
        $data = [],
        $message = 'Deleted'
    ) {
        return [
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ];
    }

    /**
     * Returns general error
     *
     * @param int $numb
     * @param string $errors
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorResponse($numb, $errors)
    {
        $response = $this->errorEnvelope($numb, $errors);

        return response()->json($response);
    }

    /**
     * Client does not have proper permissions to perform action.
     *
     * @param $errors
     * @return \Illuminate\Http\JsonResponse
     */
    protected function insufficientPrivilegesResponse($errors)
    {
        $response = $this->errorEnvelope(
            403,
            $errors,
            'Forbidden'
        );

        return response()->json($response, 403);
    }

    /**
     * Returns a list of resources
     *
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function listResponse($data)
    {
        $response = $this->successEnvelope(200, $data);

        return response()->json($response);
    }

    /**
     * Requested resource wasn't found
     *
     * @param int $status
     * @param array $errors
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function notFoundResponse($errors)
    {
        $response = $this->errorEnvelope(402, $errors, 'Not Found Data Requsted');

        return response()->json($response, 404);
    }

    /**
     * Return information for single resource
     *
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function showResponse($data)
    {
        $response = $this->successEnvelope(200, $data);

        return response()->json($response);
    }

    /**
     * Return error when request is properly formatted, but contains validation errors
     *
     * @param $errors
     * @return \Illuminate\Http\JsonResponse
     */
    protected function validationErrorResponse($errors)
    {
        $response = $this->errorEnvelope(422, $errors, 'Unprocessable Entity');

        return response()->json($response, 422);
    }

    /**
     * Standard error envelope structure
     *
     * @param int $status
     * @param array $errors
     * @param string $message
     * @return array
     */
    private function errorEnvelope(
        $numb,
        $errors,
        $message = 'Bad Request'
    ) {
        return [
            'status' => $numb,
            'message' => $message,
            'errors' => $errors,
        ];
    }

    /**
     * Standard success envelope structure
     *
     * @param int $status
     * @param string $message
     * @param array $data
     * @return array
     */
    private function successEnvelope(
        $data = []
    ) {
        return response()->json([
            'success' => true,
            'message' => 'OK',
            'data' => $data,
        ]);
    }
    /**
     * Standard message and Data Response envelope structure
     *
     * @param array $data
     * @param string $message
     * @return array
     */

    public function handleResponse($result, $msg)
    {
        $res = [
            'success' => true,
            'data'    => $result,
            'message' => $msg,
        ];
        return response()->json($res, 200);
    }
    /**
     * Standard messageONLY response envelope structure
     *
     * @param string $message
     * @return array
     */

    public function messageResponse($msg)
    {
        $res = [
            'success' => true,
            'message' => $msg,
        ];
        return response()->json($res, 200);
    }

    public function handleError($msg)
    {
        $res = [
            'success' => false,
            'message' => $msg,
        ];

        return response()->json($res);
    }

    public function customResponse($attr, $key, $value)
    {
        $attr = [
            $key => $value,
        ];

        return response()->json($attr);
    }
}
