<?php declare(strict_types=1);

namespace App\Tests\unit\Common\Encryption\Service;

use App\Common\Encryption\Service\EncryptionService;
use Codeception\Test\Unit;
use Exception;

class EncryptionServiceTest extends Unit
{
    /**
     * @test
     */
    public function I_can_encrypt_a_string_and_the_result_isnt_the_same_as_the_original_string()
    {
        $message = 'My super secret information.';
        $method = 'AES-256-CBC';
        $secret = 'My32charPasswordAndInitVectorStr';

        $encryptionService = new EncryptionService();

        $encryptedString = $encryptionService->encrypt($message, $method, $secret, $hmac);

        expect($encryptedString)->notToBeFalse();
        expect($encryptedString)->notToBe($message);
        expect($encryptedString)->notToBe($method);
        expect($encryptedString)->notToBe($secret);
        expect($hmac)->notToBeNull();
    }

    /**
     * @test
     */
    public function I_can_encrypt_and_decrypt_a_string()
    {
        $message = 'My super secret information.';
        $method = 'AES-256-CBC';
        $secret = 'My32charPasswordAndInitVectorStr';

        $encryptionService = new EncryptionService();

        $encryptedString = $encryptionService->encrypt($message, $method, $secret, $hmac);

        $decryptedString = $encryptionService->decrypt($encryptedString, $method, $secret, $hmac);

        expect($decryptedString)->toBe($message);
    }

    /**
     * @test
     */
    public function I_cannot_decrypt_a_string_using_the_wrong_hmac()
    {
        $message = 'My super secret information.';
        $method = 'AES-256-CBC';
        $secret = 'My32charPasswordAndInitVectorStr';

        $encryptionService = new EncryptionService();

        $encryptedString = $encryptionService->encrypt($message, $method, $secret, $hmac);

        $decryptedString = $encryptionService->decrypt($encryptedString, $method, $secret, 'blah');

        expect($decryptedString)->toBeFalse();
    }

    /**
     * @test
     */
    public function I_can_encrypt_a_string_with_a_timestamp()
    {
        $message = 'My super secret information.';
        $method = 'AES-256-CBC';
        $secret = 'My32charPasswordAndInitVectorStr';

        $encryptionService = new EncryptionService();

        $encryptedString = $encryptionService->encryptWithTSValidation($message, $method, $secret, $hmac);

        expect($encryptedString)->notToBeFalse();
        expect($encryptedString)->notToBe($message);
        expect($encryptedString)->notToBe($method);
        expect($encryptedString)->notToBe($secret);
        expect($hmac)->notToBeNull();
    }

    /**
     * @test
     * @throws Exception
     */
    public function I_can_encrypt_and_decrypt_a_string_with_a_timestamp()
    {
        $message = 'My super secret information.';
        $method = 'AES-256-CBC';
        $secret = 'My32charPasswordAndInitVectorStr';

        $encryptionService = new EncryptionService();

        $encryptedString = $encryptionService->encryptWithTSValidation($message, $method, $secret, $hmac);

        $decryptedString = $encryptionService->decryptWithTSValidation($encryptedString, $method, $secret, $hmac, 60);

        expect($decryptedString)->toBe($message);
    }

    /**
     * @test
     * @throws Exception
     */
    public function I_cannot_decrypt_a_string_with_a_timestamp_using_the_wrong_hmac()
    {
        $message = 'My super secret information.';
        $method = 'AES-256-CBC';
        $secret = 'My32charPasswordAndInitVectorStr';

        $encryptionService = new EncryptionService();

        $encryptedString = $encryptionService->encryptWithTSValidation($message, $method, $secret, $hmac);

        $decryptedString = $encryptionService->decryptWithTSValidation($encryptedString, $method, $secret, 'blah', 60);

        expect($decryptedString)->toBeFalse();
    }

    /**
     * @test
     * @throws Exception
     */
    public function I_cannot_decrypt_a_string_with_a_timestamp_if_I_am_outside_the_timestamp_threshold()
    {
        $message = 'My super secret information.';
        $method = 'AES-256-CBC';
        $secret = 'My32charPasswordAndInitVectorStr';

        $encryptionService = new EncryptionService();

        $encryptedString = $encryptionService->encryptWithTSValidation($message, $method, $secret, $hmac);

        sleep(2);

        $decryptedString = $encryptionService->decryptWithTSValidation($encryptedString, $method, $secret, $hmac, 1);

        expect($decryptedString)->toBeFalse();
    }
}
