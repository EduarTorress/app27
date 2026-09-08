<?php
class Resumenboletas{
    public function Generaxml($nombrexml){
    $obxml=new XMLWriter();
    $obxml->openURI($nombrexml);
    $obxml->setIndent(true);
    $obxml->setIndentString("\t");
    $obxml->startDocument('1.0', 'ISO-8859-1','no');
    $obxml->startElementNS(NULL,"SummaryDocuments",NULL);
    $obxml->writeAttribute(
        'xmlns',
        'urn:sunat:names:specification:ubl:peru:schema:xsd:SummaryDocuments-1'
      );
     $obxml->writeAttributeNS(
        'xmlns',
        'cac',
        NULL,
        'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2'
      );
      $obxml->writeAttributeNS(
        'xmlns',
        'cbc',
        NULL,
        'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2'
      );
      $obxml->writeAttributeNS(
        'xmlns',
        'ds',
        NULL,
        'http://www.w3.org/2000/09/xmldsig#'
      );
      $obxml->writeAttributeNS(
        'xmlns',
        'ext',
        NULL,
        'urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2'
      );
      $obxml->writeAttributeNS(
        'xmlns',
        'sac',
        NULL,
        'urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1'
      );
      $obxml->writeAttributeNS(
        'xmlns',
        'qdt',
        NULL,
        'urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2'
      );
      $obxml->writeAttributeNS(
        'xmlns',
        'udt',
        NULL,
        'urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2'
      );

     //Firma Digital
    $obxml->startElement("ext:UBLExtensions");
    $obxml->startElement("ext:UBLExtension");
    $obxml->writeElement("ext:ExtensionContent","");
    //$obxml->startElement("ext:ExtensionContent","");
    $obxml->endElement();
    $obxml->endElement();

    //Versión XML
    $obxml->writeElement("cbc:UBLVersionID","2.0");

    //Customsiza
    $obxml->writeElement("cbc:CustomizationID","1.1");
    $obxml->writeElement("cbc:ID","RC-20210520");
    $obxml->writeElement("cbc:ReferenceDate","2021-05-20");
    $obxml->writeElement("ccbc:IssueDate","2021-05-20");
    //Datos del que firma
    $obxml->startElement("cac:Signature");
    $obxml->writeElement("cbc:ID",'sign'.'20103135317');
    $obxml->startElement("cac:SignatoryParty");

    $obxml->startElement("cac:PartyIdentification");
    $obxml->writeElement("cbc:ID",'20103125317');
    $obxml->endElement();

    $obxml->startElement("cac:PartyName");
    $obxml->startElement("cbc:Name");
    $obxml->writeCdata("SERVICIOS AGRO INDUST.Y DE CONSTRUCC.SRL");
    $obxml->endElement();
    $obxml->endElement();

    $obxml->endElement();

    $obxml->startElement("cac:DigitalSignatureAttachment");
    $obxml->startElement("cac:ExternalReference");
    $obxml->writeElement("cbc:URI",'#sign'.'20103125317');
    $obxml->endElement();

    $obxml->endElement();

    $obxml->endElement();

    $obxml->startElement("cac:AccountingSupplierParty");
    $obxml->writeElement("cbc:CustomerAssignedAccountID","20103125317");
    $obxml->writeElement("cbc:AdditionalAccountID",'6');


    $obxml->startElement("cac:Party");
    $obxml->startElement("cac:PartyLegalEntity");
    $obxml->startElement("cbc:RegistrationName");
    $obxml->writeCdata("SERVICIOS AGRO INDUST.Y DE CONSTRUCC.SRL");
    $obxml->endElement();
    $obxml->endElement();
    $obxml->endElement();
    $obxml->endElement();

    $obxml->endElement();

    /*Linea de documentos*/
    $obxml->startElement("sac:SummaryDocumentsLine");
    $obxml->writeElement("cbc:LineID",'1');

    $obxml->writeElement("cbc:DocumentTypeCode","03");

    $obxml->writeElement("cbc:ID","B005-52");

    $obxml->startElement("cac:AccountingCustomerParty");
    $obxml->writeElement("cbc:CustomerAssignedAccountID","70822947");
    $obxml->writeElement("cbc:AdditionalAccountID","1");
    $obxml->endElement();

    $obxml->startElement("cac:Status");
    $obxml->writeElement("cbc:ConditionCode","1");
    $obxml->endElement();

    //$obxml->writeAttribute("sac:TotalAmount",htmlspecialchars('currencyID="PEN>"',ENT_XML1).'135.00');
    //$obxml->writeAttribute('dni', "xxxxxxxx-P");
    //$obxml->writeElement("sac:TotalAmount",htmlspecialchars('currencyID="PEN>"',ENT_XML1).'135.00');
    $obxml->startElement("sac:TotalAmount");
    $cmoneda="PEN";
    $obxml->writeAttribute('currencyID',$cmoneda);
    $obxml->text('135.00');
    $obxml->endElement();

    $obxml->startElement("sac:BillingPayment");
    $obxml->startElement("cbc:PaidAmount");
    $obxml->writeAttribute('currencyID',"PEN");
    $obxml->text('114.41');
    $obxml->endElement();
    $obxml->writeElement("cbc:InstructionID","01");
    $obxml->endElement();

    $obxml->startElement("cac:TaxTotal");
    $obxml->startElement("cbc:TaxAmount");
    $obxml->writeAttribute('currencyID',"PEN");
    $obxml->text('20.59');
    $obxml->endElement();

    $obxml->startElement("cac:TaxSubtotal");
    $obxml->startElement("cbc:TaxAmount");
    $obxml->writeAttribute('currencyID',"PEN");
    $obxml->text("0.59");
    $obxml->endElement();
    $obxml->startElement("cac:TaxCategory");

    $obxml->startElement("ac:TaxScheme");
    $obxml->writeElement("cbc:ID","1000");
    $obxml->writeElement("cbc:Name","IGV");
    $obxml->writeElement("cbc:TaxTypeCode","VAT");
    $obxml->endElement();

    $obxml->endElement();

    $obxml->endElement();

    $obxml->endElement();
    $obxml->endDocument();
    }
}
